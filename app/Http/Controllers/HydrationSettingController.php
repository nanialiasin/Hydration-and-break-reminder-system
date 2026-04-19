<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use App\Models\Coach;
use App\Models\HydrationSetting;
use Illuminate\Http\Request;

class HydrationSettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = HydrationSetting::all();
        $context = $this->buildAthleteContext($request);

        return view('hydration.index', array_merge(compact('settings'), $context));
    }

    public function edit(Request $request)
    {
        $settings = HydrationSetting::all();
        $context = $this->buildAthleteContext($request);

        return view('hydration.edit', array_merge(compact('settings'), $context));
    }

    public function preview(Request $request)
    {
        $settings = HydrationSetting::all();
        $context = $this->buildAthleteContext($request);

        return view('hydration.preview', array_merge(compact('settings'), $context));
    }

    public function update(Request $request)
    {
        foreach ($request->settings as $id => $data) {
            $hydrationSetting = HydrationSetting::find($id);
            if ($hydrationSetting) {
                $hydrationSetting->intensity = $data['intensity'] ?? $hydrationSetting->intensity;
                $hydrationSetting->hydration_reminder = $data['hydration_reminder'] ?? $hydrationSetting->hydration_reminder;
                $hydrationSetting->break_duration = $data['break_duration'] ?? $hydrationSetting->break_duration;
                $hydrationSetting->break_reminder = $data['break_reminder'] ?? $hydrationSetting->break_reminder;
                // Optionally sync with athlete profile
                if (!$hydrationSetting->intensity) {
                    $athlete = \App\Models\Athlete::where('athlete_id', $hydrationSetting->athlete_id)->first();
                    if ($athlete) {
                        $hydrationSetting->intensity = $athlete->intensity;
                    }
                }
                $hydrationSetting->save();
            }
        }
        return redirect()->route('hydration.index')->with('success', 'Hydration settings updated successfully.');
    }

    private function buildAthleteContext(Request $request): array
    {
        $user = auth()->user();
        $athlete = null;
        $athletes = collect();
        $coach = null;

        if ($user?->role === 'athlete') {
            $athlete = Athlete::where('email', $user->email)->first();
            $athletes = collect([$athlete])->filter()->values();
        } elseif ($user?->role === 'coach') {
            $coach = Coach::where('email', $user->email)->first();
            $coachKey = $coach?->coach_id;
            $selectedAthleteId = (string) $request->query('athlete_id', '');

            $athletes = Athlete::query()
                ->where(function ($query) use ($user, $coachKey) {
                    $query->where('created_by_coach', (string) $user->id);

                    if (!empty($coachKey)) {
                        $query->orWhere('created_by_coach', $coachKey);
                    }
                })
                ->orderBy('name')
                ->get();

            if ($selectedAthleteId !== '') {
                $athlete = $athletes->firstWhere('athlete_id', $selectedAthleteId)
                    ?? Athlete::where('athlete_id', $selectedAthleteId)->first();
            }

            $athlete ??= $athletes->first();
        }

        $selectedSetting = null;
        if ($athlete?->intensity) {
            $selectedSetting = HydrationSetting::query()
                ->whereRaw('LOWER(intensity) = ?', [strtolower((string) $athlete->intensity)])
                ->first();
        }

        $selectedSetting ??= HydrationSetting::query()->first();

        return [
            'athlete' => $athlete,
            'athletes' => $athletes,
            'coach' => $coach,
            'selectedAthleteId' => $athlete?->athlete_id,
            'selectedSetting' => $selectedSetting,
        ];
    }
}
