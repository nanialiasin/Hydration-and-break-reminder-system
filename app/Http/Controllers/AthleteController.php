<?php

namespace App\Http\Controllers;

use App\Models\Athlete;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AthleteController extends Controller
{
    public function index()
    {
        $athletes = Athlete::all();
        return view('athletes.index', compact('athletes'));
    }

    public function create()
    {
        return view('athletes.create');
    }

    public function fetch($athlete_id)
    {
        $athlete = \App\Models\Athlete::where('athlete_id', $athlete_id)->first();
        if ($athlete) {
            return response()->json([
                'name' => $athlete->name,
                'sport' => $athlete->sport,
                'intensity' => $athlete->intensity,
                'status' => $athlete->status,
            ]);
        }
        return response()->json(null, 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'athlete_id' => 'required|unique:athletes',
            'name' => 'required',
            'sport' => 'required',
            'status' => 'required',
            'intensity' => 'required',
        ]);

        Athlete::create($request->all());
        return redirect()->back()->with('success', 'Athlete added successfully.');
    }

    public function edit()
    {
        $athlete = Auth::user();
        return view('profile.editprofile', compact('athlete'));
    }

    public function update(Request $request)
    {
        $athlete = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'intensity' => 'required',
        ]);

        $heightInMeter = $request->height / 100;
        $bmi = $request->weight / ($heightInMeter * $heightInMeter);

        $athlete->update([
            'name' => $request->name,
            'weight' => $request->weight,
            'height' => $request->height,
            'intensity' => $request->intensity,
            'bmi' => round($bmi, 1),
        ]);

        // Update hydration settings for this athlete
        $hydrationSetting = \App\Models\HydrationSetting::where('athlete_id', $athlete->athlete_id)->first();
        if ($hydrationSetting) {
            $hydrationSetting->intensity = $request->intensity ?? $athlete->intensity;
            $hydrationSetting->save();
        }

        return redirect()->route('profile.athlprofile')->with('success', 'Profile updated successfully.');
    }

    public function updateProfilePic(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $athlete = Auth::user();

        // Find athlete profile using the user's email
        $athleteProfile = Athlete::where('email', $athlete->email)->first();
        if (!$athleteProfile) {
            return redirect()->back()->with('error', 'Athlete profile not found.');
        }
        $file = $request->file('profile_pic');
        $filename = 'profile_' . $athleteProfile->athlete_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/profile_pics', $filename);

        // Optionally, delete old profile pic if not default
        if ($athleteProfile->profile_pic && $athleteProfile->profile_pic !== 'default.jpg') {
            \Storage::delete('public/profile_pics/' . $athleteProfile->profile_pic);
        }

        $athleteProfile->profile_pic = $filename;
        $athleteProfile->save();

        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }

    public function updateHydrationSetting(Request $request)
    {
        $athlete = Auth::user();
        $hydrationSetting = \App\Models\HydrationSetting::where('athlete_id', $athlete->athlete_id)->first();
        if ($hydrationSetting) {
            $hydrationSetting->intensity = $request->intensity ?? $athlete->intensity;
            $hydrationSetting->hydration_reminder = $request->hydration_reminder ?? $hydrationSetting->hydration_reminder;
            $hydrationSetting->break_duration = $request->break_duration ?? $hydrationSetting->break_duration;
            $hydrationSetting->break_reminder = $request->break_reminder ?? $hydrationSetting->break_reminder;
            $hydrationSetting->save();
        }
        return redirect()->back()->with('success', 'Hydration settings updated successfully.');
    }
}
