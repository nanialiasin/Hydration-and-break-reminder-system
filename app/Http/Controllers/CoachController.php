<?php

namespace App\Http\Controllers;

use App\Models\Athlete;;

class CoachController extends Controller
{
    public function index()
    {
        $coachId = auth()->user()->id;
        $totalAthletes = Athlete::where('created_by_coach', $coachId)->count();
        $checkedIn = Athlete::where('created_by_coach', $coachId)->where('status', 'checked_in')->count();
        $notCheckedIn = Athlete::where('created_by_coach', $coachId)->where('status', 'checked_out')->count();

        return view('coach.home', compact(
            'totalAthletes',
            'checkedIn',
            'notCheckedIn'
        ));
    }
}
