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

        $totalAthletes = Athlete::where('created_by_coach', $coachId)->count();
        $checkedIn = Athlete::where('created_by_coach', $coachId)->where('status', 'checked_in')->count();
        $notCheckedIn = Athlete::where('created_by_coach', $coachId)->where('status', 'checked_out')->count();
        $athletes = Athlete::where('created_by_coach', $coachId)->get();

        return view('coach.home', compact(
            'totalAthletes',
            'checkedIn',
            'notCheckedIn',
            'athletes'
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
