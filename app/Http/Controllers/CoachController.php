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

        $sessions = HydrationSession::query()
            ->where('assigned_by_coach', true)
            ->whereNull('started_at')
            ->whereNull('completed_at')
            ->whereIn('athlete_id', $athleteIds)
            ->latest('id')
            ->get();

        $athleteNames = $athletes->pluck('name', 'athlete_id')->toArray();

        $completedCounts = HydrationSession::query()
            ->selectRaw('sport, intensity, planned_duration_minutes, COUNT(*) as completed_count')
            ->where('assigned_by_coach', true)
            ->whereNotNull('completed_at')
            ->whereIn('athlete_id', $athleteIds)
            ->groupBy('sport', 'intensity', 'planned_duration_minutes')
            ->get()
            ->mapWithKeys(function ($row) {
                $key = strtolower((string) $row->sport) . '|' . strtolower((string) $row->intensity) . '|' . (int) $row->planned_duration_minutes;
                return [$key => (int) $row->completed_count];
            })
            ->toArray();

        return view('coach.in-progress', compact('sessions', 'athleteNames', 'completedCounts'));
    }
}
