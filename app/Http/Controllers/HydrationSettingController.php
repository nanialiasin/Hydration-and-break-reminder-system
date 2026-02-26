<?php

namespace App\Http\Controllers;

use App\Models\HydrationSetting;
use Illuminate\Http\Request;

class HydrationSettingController extends Controller
{
    public function index()
    {
        $settings = HydrationSetting::all();
        return view('hydration.index', compact('settings'));
    }

    public function edit()
    {
        $settings = HydrationSetting::all();
        return view('hydration.edit', compact('settings'));
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
}
