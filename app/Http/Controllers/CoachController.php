<?php

namespace App\Http\Controllers;

use App\Models\Athlete;;

class CoachController extends Controller
{
    public function index()
    {
        $totalAthletes = Athlete::count();
        $checkedIn = Athlete::where('status', 'checked_in')->count();
        $notCheckedIn = Athlete::where('status', 'checked_out')->count();

        return view('coach.home', compact(
            'totalAthletes',
            'checkedIn',
            'notCheckedIn'
        ));
    }
}
