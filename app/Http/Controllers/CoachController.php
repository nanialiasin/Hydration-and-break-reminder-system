<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
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

        return view('coach.home', compact(
            'athletes',
            'totalAthletes',
            'activeAthletes',
            'inactiveAthletes',
            'checkedIn',
            'notCheckedIn'
        ));
    }

    public function history()
    {
        $sessions = TrainingSession::where('coach_id', auth()->id())
                    ->latest()
                    ->get();

        return view('coach.chistory', compact('sessions'));
    }
}
