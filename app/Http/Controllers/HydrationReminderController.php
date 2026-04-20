<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HydrationReminderService;
use Illuminate\Http\JsonResponse;
use App\Models\HydrationSession;
use App\Models\Athlete;
use App\Models\Coach;
use App\Models\HydrationSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HydrationReminderController extends Controller
{
    private const SENSOR_CACHE_KEY = 'hydration.latest_sensor_reading';
    private const DEFAULT_SENSOR_STALE_AFTER_SECONDS = 30;

    // Service that calculates smart hydration intervals.
    protected $hydrationService;

    // Inject hydration reminder service.
    public function __construct(HydrationReminderService $hydrationService)
    {
        $this->hydrationService = $hydrationService;
    }

    // API endpoint: returns current reminder interval and next reminder time.
    public function getReminderStatus(Request $request): JsonResponse
    {
        $sensor = $this->getSensorReading();

        $temperature = is_numeric($request->input('temperature')) ? (float) $request->input('temperature') : (float) $sensor['temperature'];
        $humidity = is_numeric($request->input('humidity')) ? (float) $request->input('humidity') : (float) $sensor['humidity'];
        $durationMinutes = (int) $request->input('duration', 60);

        $weightKg = null;
        if (is_numeric($request->input('weight'))) {
            $weightKg = (int) $request->input('weight');
        } elseif ($user = auth()->user()) {
            $athlete = Athlete::where('email', $user->email)->first();
            $weightKg = $athlete?->weight;
        }

        $intensity = $this->normalizeIntensity((string) $request->input('intensity', ''));
        $hydrationSetting = $this->findHydrationSettingByIntensity($intensity);
        $baseReminder = (int) ($hydrationSetting?->hydration_reminder ?? 30);

        // Use intensity setting as baseline and tune with environment and weight.
        $interval = $this->hydrationService->calculateAdjustedInterval(
            $baseReminder,
            $temperature,
            $humidity,
            $durationMinutes,
            $weightKg
        );

        // Compute next reminder timestamp.
        $nextReminder = $this->hydrationService->calculateNextReminder(now(), $interval);

        $payload = [
            'interval_minutes' => $interval,
            'next_reminder_at' => $nextReminder->toDateTimeString(),
        ];

        if ($weightKg && $weightKg > 0) {
            $dailyTargetMl = $this->hydrationService->calculateAdjustedDailyHydrationTarget($weightKg, $temperature, $humidity, $durationMinutes);
            $drinkMl = $this->hydrationService->calculateReminderVolume($weightKg, $temperature, $humidity, $durationMinutes);

            $payload['daily_target_ml'] = $dailyTargetMl;
            $payload['drink_size_ml'] = $drinkMl;
            $payload['message'] = "Drink about {$drinkMl}ml every {$interval} minutes to reach {$dailyTargetMl}ml today!";
        } else {
            $payload['message'] = "Based on conditions, drink water every $interval minutes.";
        }

        return response()->json($payload);
    }

    // Starts a training session and stores active session metadata.
    public function startSession(Request $request)
    {
        $user = auth()->user();

        // Coach flow: create to-do sessions for managed athletes.
        if ($user && $user->role === 'coach') {
            $sport = (string) $request->input('sport', 'General Training');
            $intensity = $this->normalizeIntensity((string) $request->input('intensity', 'beginner'));
            $totalDuration = ((int) $request->input('hours', 0) * 60) + (int) $request->input('minutes', 0);

            if ($totalDuration <= 0) {
                $totalDuration = 30;
            }

            $coach = Coach::where('email', $user->email)->first();

            $athletesQuery = Athlete::query();
            $athletesQuery->where('created_by_coach', (string) $user->id);

            if ($coach && !empty($coach->coach_id)) {
                $athletesQuery->orWhere('created_by_coach', $coach->coach_id);
            }

            $athletes = $athletesQuery->get();

            if ($athletes->isEmpty()) {
                return redirect()->route('coach.creating')->with('error', 'No athletes found to assign this session.');
            }

            $coachSetting = $this->findHydrationSettingByIntensity($intensity);
            $coachReminderInterval = (int) ($coachSetting?->hydration_reminder ?? 20);

            $createdCount = 0;

            foreach ($athletes as $athlete) {
                HydrationSession::create([
                    'athlete_id' => $athlete->athlete_id,
                    'coach_id' => (string) $user->id,
                    'assigned_by_coach' => true,
                    'sport' => $sport,
                    'intensity' => $intensity,
                    'planned_duration_minutes' => $totalDuration,
                    'actual_duration_seconds' => 0,
                    'temperature' => 32,
                    'humidity' => 74,
                    'reminder_interval_minutes' => $coachReminderInterval,
                    'alerts' => 0,
                    'followed' => 0,
                    'ignored' => 0,
                    'hydration_score' => 0,
                    'completed_at' => null,
                    'started_at' => null,
                ]);

                $createdCount++;
            }

            return redirect()->route('coach.creating')->with('success', "Session assigned to {$createdCount} athlete(s).");
        }

        $sensor = $this->getSensorReading();
        $temperature = (float) $sensor['temperature'];
        $humidity = (float) $sensor['humidity'];

        // Read selected training options.
        $sport = (string) $request->input('sport', 'General Training');
        $intensity = $this->normalizeIntensity((string) $request->input('intensity', 'beginner'));
        $totalDuration = ((int) $request->input('hours', 0) * 60) + (int) $request->input('minutes', 0);

        if ($totalDuration <= 0) {
            $totalDuration = 30;
        }

        $weightKg = null;
        if ($user) {
            $athlete = Athlete::where('email', $user->email)->first();
            $weightKg = $athlete?->weight;
        }

        // Fetch hydration settings for the selected intensity
        $hydrationSetting = $this->findHydrationSettingByIntensity($intensity);
        $defaultReminder = $hydrationSetting ? $hydrationSetting->hydration_reminder : 20;
        $breakDuration = $hydrationSetting ? $hydrationSetting->break_duration : 5;
        $breakReminder = $hydrationSetting ? $hydrationSetting->break_reminder : 15;

        // Use intensity setting as baseline and tune with environment and weight.
        $intervalMinutes = $this->hydrationService->calculateAdjustedInterval(
            (int) $defaultReminder,
            $temperature,
            $humidity,
            $totalDuration,
            $weightKg
        );

        // Save active session details for later completion.
        $assignedSessionId = $request->input('assigned_session_id');

        if ($assignedSessionId) {
            $assignedSession = HydrationSession::find((int) $assignedSessionId);

            if ($assignedSession && !$assignedSession->completed_at && !$assignedSession->started_at) {
                $assignedSession->started_at = now();
                $assignedSession->save();
            }
        }

        $request->session()->put('active_session', [
            'assigned_session_id' => $assignedSessionId,
            'sport' => $sport,
            'intensity' => $intensity,
            'planned_duration_minutes' => $totalDuration,
            'temperature' => $temperature,
            'humidity' => $humidity,
            'interval_minutes' => $intervalMinutes,
            'break_duration' => $breakDuration,
            'break_reminder' => $breakReminder,
        ]);

        // Pass everything to the session blade (for athlete)
        return view('session', [
            'interval' => $intervalMinutes,
            'breakDuration' => $breakDuration,
            'breakReminder' => $breakReminder,
            'totalDuration' => $totalDuration,
            'temp' => $temperature,
            'humidity' => $humidity,
            'sport' => $sport,
            'intensity' => $intensity,
        ]);
    }

    private function normalizeIntensity(string $intensity): string
    {
        $normalized = strtolower(trim($intensity));

        return in_array($normalized, ['beginner', 'intermediate', 'advanced'], true)
            ? $normalized
            : 'beginner';
    }

    private function findHydrationSettingByIntensity(string $intensity): ?HydrationSetting
    {
        return HydrationSetting::query()
            ->whereRaw('LOWER(intensity) = ?', [$this->normalizeIntensity($intensity)])
            ->first();
    }

    // Endpoint for Arduino/bridge to push latest sensor values.
    public function ingestSensorReading(Request $request): JsonResponse
    {
        $configuredKey = (string) config('services.hydration_sensor.key', '');
        $providedKey = (string) ($request->input('key') ?? $request->header('X-Sensor-Key', ''));

        if ($configuredKey !== '' && !hash_equals($configuredKey, $providedKey)) {
            Log::warning('Sensor ingest rejected due to invalid key.', [
                'ip' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized sensor key.',
            ], 401);
        }

        $temperatureInput = $request->input('temperature');
        $humidityInput = $request->input('humidity');

        if (!is_numeric($temperatureInput) || !is_numeric($humidityInput)) {
            Log::warning('Sensor ingest rejected due to non-numeric payload.', [
                'temperature' => $temperatureInput,
                'humidity' => $humidityInput,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'ok' => false,
                'message' => 'temperature and humidity must be numeric.',
            ], 422);
        }

        $reading = [
            'temperature' => round((float) $temperatureInput, 1),
            'humidity' => round((float) $humidityInput, 1),
            'updated_at' => now()->toDateTimeString(),
            'source' => 'sensor',
        ];

        Cache::put(self::SENSOR_CACHE_KEY, $reading, now()->addHours(6));

        return response()->json([
            'ok' => true,
            'reading' => $reading,
        ]);
    }

    // Endpoint to fetch latest sensor values.
    public function latestSensorReading(): JsonResponse
    {
        return response()->json($this->getSensorReading());
    }

    // Home page with reminder interval and daily summary stats.
    public function showHome()
    {
        $sensor = $this->getSensorReading();
        $sensorSource = (string) ($sensor['source'] ?? 'fallback');
        $hasUsableSensorReading = in_array($sensorSource, ['sensor', 'stale'], true);

        $temperature = $hasUsableSensorReading ? (float) $sensor['temperature'] : 0.0;
        $humidity = $hasUsableSensorReading ? (float) $sensor['humidity'] : 0.0;

        $intervalTemperature = $hasUsableSensorReading ? $temperature : 32.0;
        $intervalHumidity = $hasUsableSensorReading ? $humidity : 74.0;

        // Use baseline conditions for home preview interval.
        $interval = $this->hydrationService->calculateInterval($intervalTemperature, $intervalHumidity, 60);
        // Pull streak and weekly hydration average.
        [$dayStreak] = $this->getDailyStats();
        $athlete = \App\Models\Athlete::where('email', auth()->user()->email)->first();
        $weeklyAvg = $athlete?->weekly_avg ?? null;

        return view('home', [
            'interval' => $interval,
            'temp' => $temperature,
            'humidity' => $humidity,
            'dayStreak' => $dayStreak,
            'weeklyAvg' => $weeklyAvg,
            'athlete' => $athlete,
        ]);
    }

    // Streak page summary.
    public function showStreak()
    {
        [$dayStreak] = $this->getDailyStats();

        return view('streak', [
            'dayStreak' => $dayStreak,
        ]);
    }

    // Ends a session, validates metrics, stores results, and redirects to summary.
    public function endSession(Request $request)
    {
        // Retrieve active session defaults.
        $activeSession = $request->session()->get('active_session', []);
        $assignedSessionId = isset($activeSession['assigned_session_id'])
            ? (int) $activeSession['assigned_session_id']
            : null;

        $athleteId = null;
        $user = auth()->user();
        if ($user) {
            $athlete = Athlete::where('email', $user->email)->first();
            $athleteId = $athlete?->athlete_id;
        }

        // Normalize posted session metrics.
        $alerts = max(0, (int) $request->input('alerts', 0));
        $followed = max(0, (int) $request->input('followed', 0));
        $ignored = max(0, (int) $request->input('ignored', 0));
        $durationSeconds = max(0, (int) $request->input('duration_seconds', 0));

        // Keep followed/ignored totals within alert count.
        if (($followed + $ignored) > $alerts) {
            $ignored = max(0, $alerts - $followed);
        }

        // Hydration score = followed reminders / total alerts.
        $hydrationScore = $alerts > 0
            ? (int) round(($followed / $alerts) * 100)
            : 0;

        $savedSession = null;

        // Complete assigned coach task when provided.
        if ($assignedSessionId) {
            $assignedSession = HydrationSession::find($assignedSessionId);

            if ($assignedSession && !$assignedSession->completed_at) {
                $assignedSession->sport = $activeSession['sport'] ?? $assignedSession->sport;
                $assignedSession->intensity = $activeSession['intensity'] ?? $assignedSession->intensity;
                $assignedSession->planned_duration_minutes = (int) ($activeSession['planned_duration_minutes'] ?? $assignedSession->planned_duration_minutes);
                $assignedSession->actual_duration_seconds = $durationSeconds;
                $assignedSession->temperature = (int) ($activeSession['temperature'] ?? 32);
                $assignedSession->humidity = (int) ($activeSession['humidity'] ?? 74);
                $assignedSession->reminder_interval_minutes = (int) ($activeSession['interval_minutes'] ?? 20);
                $assignedSession->alerts = $alerts;
                $assignedSession->followed = $followed;
                $assignedSession->ignored = $ignored;
                $assignedSession->hydration_score = $hydrationScore;
                $assignedSession->started_at = $assignedSession->started_at ?: now()->subSeconds($durationSeconds);
                $assignedSession->completed_at = now();
                if ($athleteId && empty($assignedSession->athlete_id)) {
                    $assignedSession->athlete_id = $athleteId;
                }
                $assignedSession->save();

                $savedSession = $assignedSession;
            }
        }

        // Fallback: persist as standalone completed session.
        if (!$savedSession) {
            $savedSession = HydrationSession::create([
                'athlete_id' => $athleteId,
                'assigned_by_coach' => false,
                'sport' => $activeSession['sport'] ?? 'General Training',
                'intensity' => $activeSession['intensity'] ?? 'beginner',
                'planned_duration_minutes' => (int) ($activeSession['planned_duration_minutes'] ?? 0),
                'actual_duration_seconds' => $durationSeconds,
                'temperature' => (int) ($activeSession['temperature'] ?? 32),
                'humidity' => (int) ($activeSession['humidity'] ?? 74),
                'reminder_interval_minutes' => (int) ($activeSession['interval_minutes'] ?? 20),
                'alerts' => $alerts,
                'followed' => $followed,
                'ignored' => $ignored,
                'hydration_score' => $hydrationScore,
                'started_at' => now()->subSeconds($durationSeconds),
                'completed_at' => now(),
            ]);
        }

        // Remove active session once saved.
        $request->session()->forget('active_session');

        // Redirect to completion page with flash summary.
        return redirect()
            ->route('session.completed')
            ->with('session_summary', [
                'session_id' => $savedSession->id,
                'alerts' => $alerts,
                'followed' => $followed,
                'ignored' => $ignored,
                'duration_seconds' => $durationSeconds,
                'hydration_score' => $hydrationScore,
            ]);
    }

    // Session completed page with formatted summary values.
    public function showSessionCompleted(Request $request)
    {
        // Start from flash session summary.
        $summary = $request->session()->get('session_summary', []);

        $sessionIdFromQuery = (int) $request->query('session_id', 0);
        $sessionIdFromSummary = (int) ($summary['session_id'] ?? 0);
        $targetSessionId = $sessionIdFromQuery > 0 ? $sessionIdFromQuery : $sessionIdFromSummary;

        // Prefer persisted DB values when session ID is available.
        if ($targetSessionId > 0) {
            $storedSession = HydrationSession::find($targetSessionId);

            if ($storedSession) {
                $viewer = auth()->user();

                // Athletes can only open completed sessions that belong to their athlete profile.
                if ($viewer && $viewer->role === 'athlete') {
                    $viewerAthleteId = Athlete::where('email', $viewer->email)->value('athlete_id');

                    if ($viewerAthleteId && $storedSession->athlete_id !== $viewerAthleteId) {
                        abort(403);
                    }
                }

                $summary = [
                    'session_id' => $storedSession->id,
                    'duration_seconds' => $storedSession->actual_duration_seconds,
                    'alerts' => $storedSession->alerts,
                    'followed' => $storedSession->followed,
                    'ignored' => $storedSession->ignored,
                    'hydration_score' => $storedSession->hydration_score,
                ];
            }
        }

        // Convert duration into a user-friendly text format.
        $durationSeconds = max(0, (int) ($summary['duration_seconds'] ?? 0));
        $hours = intdiv($durationSeconds, 3600);
        $minutes = intdiv($durationSeconds % 3600, 60);
        $seconds = $durationSeconds % 60;

        if ($hours > 0) {
            $durationText = "{$hours}hr {$minutes}min";
        } elseif ($minutes > 0) {
            $durationText = "{$minutes}min {$seconds}s";
        } else {
            $durationText = "{$seconds}s";
        }

        // Render completion summary.
        return view('session-completed', [
            'durationText' => $durationText,
            'alerts' => max(0, (int) ($summary['alerts'] ?? 0)),
            'followed' => max(0, (int) ($summary['followed'] ?? 0)),
            'ignored' => max(0, (int) ($summary['ignored'] ?? 0)),
            'hydrationScore' => max(0, min(100, (int) ($summary['hydration_score'] ?? 0))),
        ]);
    }

    // History page with all completed sessions.
    public function showHistory()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to view your history.');
        }

        $athlete = Athlete::where('email', $user->email)->first();

        // If there is no athlete profile linked to this account,
        // never fall back to global history.
        if (!$athlete) {
            return view('history', [
                'sessions' => collect(),
                'athlete' => null,
            ]);
        }

        $query = HydrationSession::query()
            ->whereNotNull('completed_at')
            ->where('athlete_id', $athlete->athlete_id);

        // Build display-friendly session list.
        $sessions = $query
            ->latest('completed_at')
            ->latest('id')
            ->get()
            ->map(function (HydrationSession $session) {
                return [
                    'id' => $session->id,
                    'sport' => $session->sport ?: 'General Training',
                    'date' => optional($session->completed_at)->diffForHumans() ?? $session->created_at->diffForHumans(),
                    'duration' => $this->formatDuration($session->actual_duration_seconds),
                    'hydration_score' => max(0, min(100, (int) $session->hydration_score)),
                ];
            });

        return view('history', [
            'sessions' => $sessions,
            'athlete' => $athlete,
        ]);
    }

    // Helper: format seconds into compact duration text.
    private function formatDuration(int $durationSeconds): string
    {
        $durationSeconds = max(0, $durationSeconds);
        $hours = intdiv($durationSeconds, 3600);
        $minutes = intdiv($durationSeconds % 3600, 60);
        $seconds = $durationSeconds % 60;

        if ($hours > 0) {
            return "{$hours}hr {$minutes}min";
        }

        if ($minutes > 0) {
            return "{$minutes}min {$seconds}s";
        }

        return "{$seconds}s";
    }

    // Helper: calculate current day streak and 7-day average hydration amount.
    private function getDailyStats(): array
    {
        // Fetch completed sessions needed for streak and weekly metrics.
        $sessions = HydrationSession::query()
            ->whereNotNull('completed_at')
            ->get(['completed_at', 'followed']);

        // Get unique days with at least one completed session.
        $completedDates = $sessions
            ->map(fn (HydrationSession $session) => optional($session->completed_at)?->toDateString())
            ->filter()
            ->unique()
            ->values()
            ->all();

        // Compute consecutive day streak from today backwards.
        $completedDateSet = array_flip($completedDates);
        $dayStreak = 0;
        $cursor = Carbon::today();

        while (isset($completedDateSet[$cursor->toDateString()])) {
            $dayStreak++;
            $cursor->subDay();
        }

        // Weekly average hydration in ml (250ml per followed alert).
        $weekStart = Carbon::today()->subDays(6)->startOfDay();
        $weeklyTotalMl = $sessions
            ->filter(fn (HydrationSession $session) => $session->completed_at && $session->completed_at->greaterThanOrEqualTo($weekStart))
            ->sum(fn (HydrationSession $session) => max(0, (int) $session->followed) * 250);

        $weeklyAvg = (int) round($weeklyTotalMl / 7);

        return [$dayStreak, $weeklyAvg];
    }

    // Helper: get latest live sensor reading or fallback defaults.
    private function getSensorReading(): array
    {
        $cached = Cache::get(self::SENSOR_CACHE_KEY);
        $staleAfterSeconds = $this->sensorStaleAfterSeconds();

        if (is_array($cached) && isset($cached['temperature'], $cached['humidity'])) {
            $updatedAtRaw = (string) ($cached['updated_at'] ?? now()->toDateTimeString());

            try {
                $ageSeconds = Carbon::parse($updatedAtRaw)->diffInSeconds(now());
            } catch (\Throwable $e) {
                $ageSeconds = $staleAfterSeconds + 1;
            }

            if ($ageSeconds <= $staleAfterSeconds) {
                return [
                    'temperature' => round((float) $cached['temperature'], 1),
                    'humidity' => round((float) $cached['humidity'], 1),
                    'updated_at' => $updatedAtRaw,
                    'source' => 'sensor',
                ];
            }

            // Keep the last known reading so USB disconnections don't hard-reset UI values to zero.
            return [
                'temperature' => round((float) $cached['temperature'], 1),
                'humidity' => round((float) $cached['humidity'], 1),
                'updated_at' => $updatedAtRaw,
                'source' => 'stale',
            ];
        }

        return [
            'temperature' => 0.0,
            'humidity' => 0.0,
            'updated_at' => now()->toDateTimeString(),
            'source' => 'fallback',
        ];
    }

    private function sensorStaleAfterSeconds(): int
    {
        $configured = (int) config('services.hydration_sensor.stale_after_seconds', self::DEFAULT_SENSOR_STALE_AFTER_SECONDS);

        return max(1, $configured);
    }
}