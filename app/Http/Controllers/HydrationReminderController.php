<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HydrationReminderService;
use Illuminate\Http\JsonResponse;

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
        // Calculate total duration from dropdowns
        $totalDuration = ($request->input('hour', 0) * 60) + $request->input('minutes', 0);

        // Logic: Calculate the smart interval
        $intervalMinutes = $this->hydrationService->calculateInterval(32, 74, $totalDuration);

        // Pass everything to the session blade
        return view('session', [
            'interval' => $intervalMinutes,
            'totalDuration' => $totalDuration,
            'temp' => 32,
            'humidity' => 74
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
        return redirect()->route('session.completed');
    }
}