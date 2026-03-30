<?php

namespace App\Http\Controllers;

use App\Models\Athlete;;
use App\Models\Coach;
use App\Models\HydrationSession;

class CoachController extends Controller
{
    public function index()
    {
        $coachId = auth()->user()->id;
        $athletes = Athlete::where('created_by_coach', $coachId)->get();
        $totalAthletes = $athletes->count();
        $activeAthletes = $athletes->where('status', 'active')->count();
        $inactiveAthletes = $athletes->where('status', 'inactive')->count();
        $checkedIn = $athletes->where('status', 'checked_in')->count();
        $notCheckedIn = $athletes->where('status', 'checked_out')->count();

        return view('coach.home', compact(
            'athletes',
            'totalAthletes',
            'activeAthletes',
            'inactiveAthletes',
            'checkedIn',
            'notCheckedIn'
        ));
    }

    public function inProgressSessions()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $coach = Coach::where('email', $user->email)->first();

        $sessionsQuery = HydrationSession::query()
            ->where('assigned_by_coach', true)
            ->whereNull('completed_at')
            ->where(function ($query) use ($user, $coach) {
                $query->where('coach_id', (string) $user->id);

                if ($coach && !empty($coach->coach_id)) {
                    $query->orWhere('coach_id', $coach->coach_id);
                }
            })
            ->latest('started_at')
            ->latest('id');

        $sessions = $sessionsQuery->get();

        $athleteNames = Athlete::whereIn('athlete_id', $sessions->pluck('athlete_id')->filter()->unique()->values())
            ->pluck('name', 'athlete_id');

        return view('coach.in-progress', [
            'sessions' => $sessions,
            'athleteNames' => $athleteNames,
        ]);
    }
}
