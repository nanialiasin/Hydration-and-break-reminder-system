<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\TrainingSession;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $activeAthletes = Athlete::where('status', 'active')->count();
        $inactiveAthletes = Athlete::where('status', 'inactive')->count();
        $totalAthletes = Athlete::count();

        $session = TrainingSession::latest()->first();

        $athletes = Athlete::all();

        $intensityValues = [
            'beginner' => $session ? $session->beginner_duration : null,
            'intermediate' => $session ? $session->intermediate_duration : null,
            'advanced' => $session ? $session->advanced_duration : null,
        ];

        $totalIntensity = 0;

        foreach ($athletes as $athlete) {
            if (isset($intensityValues[$athlete->intensity])) {
                $totalIntensity += $intensityValues[$athlete->intensity];
            }
        }

        $averageIntensity = null;

        if ($totalAthletes > 0) {
            $averageIntensity = round($totalIntensity / $totalAthletes);

            $reverseIntensity = array_flip($intensityValues);
            $averageIntensity = $reverseIntensity[$averageIntensity] ?? null;
        }

        return view('coach.dashboard', compact(
            'activeAthletes',
            'inactiveAthletes',
            'totalAthletes',
            'session',
            'averageIntensity'
        ));
    }
}
