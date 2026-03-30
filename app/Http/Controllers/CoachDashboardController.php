<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\TrainingSession;

class CoachDashboardController extends Controller
{
    public function index()
    {
        $coachId = auth()->check() ? auth()->user()->id : null;
        
        $activeAthletes = Athlete::where('status', 'active')->count();
        $inactiveAthletes = Athlete::where('status', 'inactive')->count();
        $totalAthletes = Athlete::count();

        // Initialize or update session durations for each intensity
        $intensityKeys = ['beginner', 'intermediate', 'advanced'];
        $sessionDurations = session()->get('session_durations', [
            'beginner' => 0,
            'intermediate' => 0,
            'advanced' => 0,
            'sport' => null,
        ]);
        if (session()->has('active_session')) {
            $activeSession = session('active_session');
            $selectedIntensity = $activeSession['intensity'] ?? null;
            $duration = $activeSession['planned_duration_minutes'] ?? 0;
            $sport = $activeSession['sport'] ?? null;
            if (in_array($selectedIntensity, $intensityKeys)) {
                $sessionDurations[$selectedIntensity] = $duration;
                $sessionDurations['sport'] = $sport;
                session(['session_durations' => $sessionDurations]);
            }
        }
        $session = (object) [
            'sport' => $sessionDurations['sport'],
            'beginner_duration' => $sessionDurations['beginner'],
            'intermediate_duration' => $sessionDurations['intermediate'],
            'advanced_duration' => $sessionDurations['advanced'],
        ];

        // Athletes created by the current coach
        $athletes = $coachId ? Athlete::where('created_by_coach', $coachId)->get() : collect();
        
        // Available athletes (not created by any coach)
        $availableAthletes = Athlete::whereNull('created_by_coach')->get();

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

        $manualAthleteCount = $athletes->count();
        $coachHasNotAddedAthletes = $manualAthleteCount == 0;

        // Only calculate averageIntensity if coach has added athletes
        if (!$coachHasNotAddedAthletes && $totalAthletes > 0) {
            $averageIntensity = round($totalIntensity / $totalAthletes);
            $reverseIntensity = array_flip(array_filter($intensityValues, fn($v) => is_string($v) || is_int($v)));
            $averageIntensity = $reverseIntensity[$averageIntensity] ?? null;
        }

        return view('coach.creating', compact(
            'activeAthletes',
            'inactiveAthletes',
            'totalAthletes',
            'session',
            'averageIntensity',
            'coachHasNotAddedAthletes',
            'athletes',
            'availableAthletes'
        ));
    }
}
