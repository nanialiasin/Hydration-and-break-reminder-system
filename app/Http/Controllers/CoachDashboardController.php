<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Coach;
use App\Models\HydrationSession;
use App\Models\TrainingSession;
use Illuminate\Http\Request;

class CoachDashboardController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }
        $coachId = auth()->user()->id;
        $sessions = HydrationSession::where('coach_id', $coachId)->latest()->get();
        $athletes = Athlete::where('created_by_coach', $coachId)->get();
        $activeAthletes = $athletes->where('status', 'active')->count();
        $inactiveAthletes = $athletes->where('status', 'inactive')->count();
        $totalAthletes = $athletes->count();
        $manualAthleteCount = $athletes->count();
        $coachHasNotAddedAthletes = $manualAthleteCount == 0;

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
            'sessions'
        ));
    }

    public function storeAthlete(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required',
            'name' => 'required',
            'sport' => 'required',
            'training_intensity' => 'required',
            'status' => 'required',
        ]);
        $athlete = Athlete::where('athlete_id', $request->athlete_id)->first();
        if ($athlete) {
            // Update existing athlete
            $athlete->update([
                'name' => $request->name,
                'sport' => $request->sport,
                'intensity' => $request->training_intensity,
                'status' => $request->status,
                'created_by_coach' => $request->created_by_coach,
            ]);
        } else {
            // Create new athlete
            $athlete = Athlete::create([
                'athlete_id' => $request->athlete_id,
                'name' => $request->name,
                'sport' => $request->sport,
                'intensity' => $request->training_intensity,
                'status' => $request->status,
                'created_by_coach' => $request->created_by_coach,
            ]);
        }

        if ($athlete && auth()->check()) {
            $this->assignPendingCoachSessionsToAthlete(auth()->user(), $athlete);
        }

        return redirect()->route('coach.creating')->with('success', 'Athlete added to your team overview!');
    }

    private function assignPendingCoachSessionsToAthlete($coachUser, Athlete $athlete): int
    {
        $coachCode = Coach::where('email', $coachUser->email)->value('coach_id');

        $coachAthleteIds = Athlete::query()
            ->where(function ($query) use ($coachUser, $coachCode) {
                $query->where('created_by_coach', (string) $coachUser->id)
                      ->orWhere('created_by_coach', $coachUser->id);

                if (!empty($coachCode)) {
                    $query->orWhere('created_by_coach', $coachCode);
                }
            })
            ->where('athlete_id', '!=', $athlete->athlete_id)
            ->pluck('athlete_id')
            ->filter()
            ->unique()
            ->values();

        if ($coachAthleteIds->isEmpty()) {
            return 0;
        }

        $pendingTemplates = HydrationSession::query()
            ->where('assigned_by_coach', true)
            ->whereNull('started_at')
            ->whereNull('completed_at')
            ->whereIn('athlete_id', $coachAthleteIds)
            ->latest('id')
            ->get();

        $assignedCount = 0;

        foreach ($pendingTemplates as $template) {
            $alreadyExists = HydrationSession::query()
                ->where('athlete_id', $athlete->athlete_id)
                ->where('assigned_by_coach', true)
                ->whereNull('started_at')
                ->whereNull('completed_at')
                ->where('sport', $template->sport)
                ->where('intensity', $template->intensity)
                ->where('planned_duration_minutes', $template->planned_duration_minutes)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            HydrationSession::create([
                'athlete_id' => $athlete->athlete_id,
                'coach_id' => $template->coach_id ?: (string) $coachUser->id,
                'assigned_by_coach' => true,
                'sport' => $template->sport,
                'intensity' => $template->intensity,
                'planned_duration_minutes' => (int) ($template->planned_duration_minutes ?? 0),
                'actual_duration_seconds' => 0,
                'temperature' => $template->temperature,
                'humidity' => $template->humidity,
                'reminder_interval_minutes' => $template->reminder_interval_minutes,
                'alerts' => 0,
                'followed' => 0,
                'ignored' => 0,
                'hydration_score' => 0,
                'started_at' => null,
                'completed_at' => null,
            ]);

            $assignedCount++;
        }

        return $assignedCount;
    }
}
