<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Coach;
use App\Models\HydrationSession;
use App\Models\TrainingSession;

class CoachController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }
        $coachId = auth()->user()->id;
        $athletes = Athlete::where('created_by_coach', $coachId)->get();
        $totalAthletes = $athletes->count();
        $activeAthletes = $athletes->where('status', 'active')->count();
        $inactiveAthletes = $athletes->where('status', 'inactive')->count();
        $checkedIn = $athletes->where('status', 'checked_in')->count();
        $notCheckedIn = $athletes->where('status', 'checked_out')->count();

        $intensityScale = [
            'beginner' => 1,
            'intermediate' => 2,
            'advanced' => 3,
        ];
        $reverseScale = array_flip($intensityScale);

        $intensityValues = $athletes
            ->map(function ($athlete) use ($intensityScale) {
                $raw = strtolower((string) ($athlete->training_intensity ?? $athlete->intensity ?? ''));
                return $intensityScale[$raw] ?? null;
            })
            ->filter()
            ->values();

        $averageIntensity = null;
        if ($intensityValues->count() > 0) {
            $roundedAverage = (int) round($intensityValues->avg());
            $averageIntensity = ucfirst($reverseScale[$roundedAverage] ?? 'Unknown');
        }

        $lastUpdatedAt = $athletes->max('updated_at');
        $lastUpdated = $lastUpdatedAt ? $lastUpdatedAt->format('d M Y, h:i A') : 'N/A';

        return view('coach.home', compact(
            'athletes',
            'totalAthletes',
            'activeAthletes',
            'inactiveAthletes',
            'checkedIn',
            'notCheckedIn',
            'averageIntensity',
            'lastUpdated'
        ));
    }

    public function history()
    {
        $sessions = TrainingSession::where('coach_id', auth()->id())
                    ->latest()
                    ->get();

        return view('coach.chistory', compact('sessions'));
    }

    public function inProgressSessions()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = auth()->user();
        $coachCode = Coach::where('email', $user->email)->value('coach_id');

        $athletes = Athlete::query()
            ->where(function ($query) use ($user, $coachCode) {
                $query->where('created_by_coach', (string) $user->id)
                      ->orWhere('created_by_coach', $user->id);

                if (!empty($coachCode)) {
                    $query->orWhere('created_by_coach', $coachCode);
                }
            })
            ->get(['athlete_id', 'name']);

        $athleteIds = $athletes->pluck('athlete_id')->filter()->unique()->values();

        $tasks = HydrationSession::query()
            ->selectRaw(
                'sport,
                 intensity,
                 planned_duration_minutes,
                 COUNT(*) as total_athletes,
                 SUM(CASE WHEN completed_at IS NOT NULL THEN 1 ELSE 0 END) as completed_athletes,
                 SUM(CASE WHEN started_at IS NOT NULL AND completed_at IS NULL THEN 1 ELSE 0 END) as in_progress_athletes,
                 MAX(id) as latest_id'
            )
            ->where('assigned_by_coach', true)
            ->whereIn('athlete_id', $athleteIds)
            ->groupBy('sport', 'intensity', 'planned_duration_minutes')
            ->orderByDesc('latest_id')
            ->get();

        $pendingTasks = $tasks->filter(function ($task) {
            return (int) $task->completed_athletes < (int) $task->total_athletes;
        })->values();

        $completedTasks = $tasks->filter(function ($task) {
            return (int) $task->completed_athletes >= (int) $task->total_athletes;
        })->values();

        return view('coach.in-progress', compact('pendingTasks', 'completedTasks'));
    }
}
