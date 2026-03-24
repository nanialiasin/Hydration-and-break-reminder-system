<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HydrationReminderService;
use Illuminate\Http\JsonResponse;
use App\Models\HydrationSession;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HydrationReminderController extends Controller
{
    private const SENSOR_CACHE_KEY = 'hydration.latest_sensor_reading';
    private const SENSOR_STALE_AFTER_SECONDS = 8;

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

        // Calculate interval from current weather/session inputs.
        $interval = $this->hydrationService->calculateInterval(
            is_numeric($request->input('temperature')) ? (float) $request->input('temperature') : (float) $sensor['temperature'],
            is_numeric($request->input('humidity')) ? (float) $request->input('humidity') : (float) $sensor['humidity'],
            $request->input('duration', 60)
        );

        // Compute next reminder timestamp.
        $nextReminder = $this->hydrationService->calculateNextReminder(now(), $interval);

        // Return reminder payload for frontend use.
        return response()->json([
            'interval_minutes' => $interval,
            'next_reminder_at' => $nextReminder->toDateTimeString(),
            'message' => "Based on conditions, drink water every $interval minutes.",
        ]);
    }

    // Starts a training session and stores active session metadata.
    public function startSession(Request $request)
    {
        $sensor = $this->getSensorReading();
        $temperature = (float) $sensor['temperature'];
        $humidity = (float) $sensor['humidity'];

        // Read selected training options.
        $sport = (string) $request->input('sport', 'General Training');
        $intensity = (string) $request->input('intensity', 'beginner');
        $totalDuration = ((int) $request->input('hours', 0) * 60) + (int) $request->input('minutes', 0);

        if ($totalDuration <= 0) {
            $totalDuration = 30;
        }

        // Fetch hydration settings for the selected intensity
        $hydrationSetting = \App\Models\HydrationSetting::where('intensity', $intensity)->first();
        $defaultReminder = $hydrationSetting ? $hydrationSetting->hydration_reminder : 20;
        $breakDuration = $hydrationSetting ? $hydrationSetting->break_duration : 5;
        $breakReminder = $hydrationSetting ? $hydrationSetting->break_reminder : 15;

        // Logic: Calculate the smart interval
        $intervalMinutes = $this->hydrationService->calculateInterval($temperature, $humidity, $totalDuration);

        // Use configured fallback reminder if the computed value is invalid.
        if ($intervalMinutes <= 0) {
            $intervalMinutes = $defaultReminder;
        }

        // Save active session details for later completion.
        $request->session()->put('active_session', [
            'sport' => $sport,
            'intensity' => $intensity,
            'planned_duration_minutes' => $totalDuration,
            'temperature' => $temperature,
            'humidity' => $humidity,
            'interval_minutes' => $intervalMinutes,
            'break_duration' => $breakDuration,
            'break_reminder' => $breakReminder,
        ]);

        // Pass everything to the session blade
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

    // Endpoint for Arduino/bridge to push latest sensor values.
    public function ingestSensorReading(Request $request): JsonResponse
    {
        $configuredKey = (string) config('services.hydration_sensor.key', '');
        $providedKey = (string) ($request->input('key') ?? $request->header('X-Sensor-Key', ''));

        if ($configuredKey !== '' && !hash_equals($configuredKey, $providedKey)) {
            return response()->json([
                'ok' => false,
                'message' => 'Unauthorized sensor key.',
            ], 401);
        }

        $temperatureInput = $request->input('temperature');
        $humidityInput = $request->input('humidity');

        if (!is_numeric($temperatureInput) || !is_numeric($humidityInput)) {
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
        $hasLiveSensorReading = (($sensor['source'] ?? 'fallback') === 'sensor');

        $temperature = $hasLiveSensorReading ? (float) $sensor['temperature'] : 0.0;
        $humidity = $hasLiveSensorReading ? (float) $sensor['humidity'] : 0.0;

        $intervalTemperature = $hasLiveSensorReading ? $temperature : 32.0;
        $intervalHumidity = $hasLiveSensorReading ? $humidity : 74.0;

        // Use baseline conditions for home preview interval.
        $interval = $this->hydrationService->calculateInterval($intervalTemperature, $intervalHumidity, 60);
        // Pull streak and weekly hydration average.
        [$dayStreak, $weeklyAvgMl] = $this->getDailyStats();
        return view('home', [
            'interval' => $interval,
            'temp' => $temperature,
            'humidity' => $humidity,
            'dayStreak' => $dayStreak,
            'weeklyAvg' => $weeklyAvgMl,
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

        // Persist completed session.
        $savedSession = HydrationSession::create([
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
            'completed_at' => now(),
        ]);

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

        // Prefer persisted DB values when session ID is available.
        if (!empty($summary['session_id'])) {
            $storedSession = HydrationSession::find((int) $summary['session_id']);

            if ($storedSession) {
                $summary = [
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
        // Build display-friendly session list.
        $sessions = HydrationSession::query()
            ->latest('completed_at')
            ->latest('id')
            ->get()
            ->map(function (HydrationSession $session) {
                return [
                    'sport' => $session->sport ?: 'General Training',
                    'date' => optional($session->completed_at)->diffForHumans() ?? $session->created_at->diffForHumans(),
                    'duration' => $this->formatDuration($session->actual_duration_seconds),
                    'hydration_score' => max(0, min(100, (int) $session->hydration_score)),
                ];
            });

        return view('history', [
            'sessions' => $sessions,
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

        $weeklyAvgMl = (int) round($weeklyTotalMl / 7);

        return [$dayStreak, $weeklyAvgMl];
    }

    // Helper: get latest live sensor reading or fallback defaults.
    private function getSensorReading(): array
    {
        $cached = Cache::get(self::SENSOR_CACHE_KEY);

        if (is_array($cached) && isset($cached['temperature'], $cached['humidity'])) {
            $updatedAtRaw = (string) ($cached['updated_at'] ?? now()->toDateTimeString());

            try {
                $ageSeconds = Carbon::parse($updatedAtRaw)->diffInSeconds(now());
            } catch (\Throwable $e) {
                $ageSeconds = self::SENSOR_STALE_AFTER_SECONDS + 1;
            }

            if ($ageSeconds <= self::SENSOR_STALE_AFTER_SECONDS) {
                return [
                    'temperature' => round((float) $cached['temperature'], 1),
                    'humidity' => round((float) $cached['humidity'], 1),
                    'updated_at' => $updatedAtRaw,
                    'source' => 'sensor',
                ];
            }

            return [
                'temperature' => 0.0,
                'humidity' => 0.0,
                'updated_at' => now()->toDateTimeString(),
                'source' => 'fallback',
            ];
        }

        return [
            'temperature' => 0.0,
            'humidity' => 0.0,
            'updated_at' => now()->toDateTimeString(),
            'source' => 'fallback',
        ];
    }
}