<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HydrationReminderService;
use Illuminate\Http\JsonResponse;
use App\Models\HydrationSession;

class HydrationReminderController extends Controller
{
    protected $hydrationService;

    public function __construct(HydrationReminderService $hydrationService)
    {
        $this->hydrationService = $hydrationService;
    }

    public function getReminderStatus(Request $request): JsonResponse
    {
        $interval = $this->hydrationService->calculateInterval(
            $request->input('temperature', 32), 
            $request->input('humidity', 74),
            $request->input('duration', 60)
        );

        $nextReminder = $this->hydrationService->calculateNextReminder(now(), $interval);

        return response()->json([
            'interval_minutes' => $interval,
            'next_reminder_at' => $nextReminder->toDateTimeString(),
            'message' => "Based on conditions, drink water every $interval minutes.",
        ]);
    }

    public function startSession(Request $request)
    {
        $sport = (string) $request->input('sport', 'General Training');
        $intensity = (string) $request->input('intensity', 'beginner');

        // Calculate total duration from dropdowns
        $totalDuration = ((int) $request->input('hours', 0) * 60) + (int) $request->input('minutes', 0);

        if ($totalDuration <= 0) {
            $totalDuration = 30;
        }

        // Logic: Calculate the smart interval
        $intervalMinutes = $this->hydrationService->calculateInterval(32, 74, $totalDuration);

        $request->session()->put('active_session', [
            'sport' => $sport,
            'intensity' => $intensity,
            'planned_duration_minutes' => $totalDuration,
            'temperature' => 32,
            'humidity' => 74,
            'interval_minutes' => $intervalMinutes,
        ]);

        // Pass everything to the session blade
        return view('session', [
            'interval' => $intervalMinutes,
            'totalDuration' => $totalDuration,
            'temp' => 32,
            'humidity' => 74,
            'sport' => $sport,
            'intensity' => $intensity,
        ]);
    }

    public function showHome()
{
    // Use logic for the dashboard (32°C, 74%)
    $interval = $this->hydrationService->calculateInterval(32, 74, 60);

    return view('home', [
        'interval' => $interval,
        'temp' => 32,
        'humidity' => 74
    ]);
}

    public function endSession(Request $request)
    {
        $activeSession = $request->session()->get('active_session', []);

        $alerts = max(0, (int) $request->input('alerts', 0));
        $followed = max(0, (int) $request->input('followed', 0));
        $ignored = max(0, (int) $request->input('ignored', 0));
        $durationSeconds = max(0, (int) $request->input('duration_seconds', 0));

        if (($followed + $ignored) > $alerts) {
            $ignored = max(0, $alerts - $followed);
        }

        $hydrationScore = $alerts > 0
            ? (int) round(($followed / $alerts) * 100)
            : 0;

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

        $request->session()->forget('active_session');

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

    public function showSessionCompleted(Request $request)
    {
        $summary = $request->session()->get('session_summary', []);

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

        return view('session-completed', [
            'durationText' => $durationText,
            'alerts' => max(0, (int) ($summary['alerts'] ?? 0)),
            'followed' => max(0, (int) ($summary['followed'] ?? 0)),
            'ignored' => max(0, (int) ($summary['ignored'] ?? 0)),
            'hydrationScore' => max(0, min(100, (int) ($summary['hydration_score'] ?? 0))),
        ]);
    }

    public function showHistory()
    {
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
}