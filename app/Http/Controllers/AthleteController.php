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

    public function create(Request $request)
    {
        $name = $request->input('name', Auth::user() ? Auth::user()->name : '');
        $email = $request->input('email', Auth::user() ? Auth::user()->email : '');
        $weight = $request->input('weight', Auth::user()->weight ?? '');
        $height = $request->input('height', Auth::user()->height ?? '');
        $sport = $request->input('sport', Auth::user()->sport ?? '');
        $training_intensity = $request->input('training_intensity', Auth::user()->training_intensity ?? '');
        return view('profile.createprofile', compact('name', 'email', 'weight', 'height', 'sport', 'training_intensity'));
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
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to create an athlete profile.');
        }
        // Check if athlete profile already exists for this user
        $existing = Athlete::where('email', $user->email)->first();
        if ($existing) {
            // Redirect to existing profile
            return redirect()->route('profile.athlprofile', ['athlete_id' => $existing->athlete_id]);
        }
        $request->validate([
            'name' => 'required',
            'sport' => 'required',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'training_intensity' => 'required',
        ]);
        // Use Athlete model's ID generation for consistency
        $athlete = Athlete::create([
            'user_id' => $user->id,
            'athlete_id' => Athlete::generateAthleteId(),
            'name' => $request->name,
            'email' => $user->email,
            'sport' => $request->sport,
            'weight' => $request->weight,
            'height' => $request->height,
            'bmi' => round($request->weight / pow($request->height / 100, 2), 2),
            'intensity' => $request->training_intensity,
            'status' => 'active',
            'created_by_coach' => $request->created_by_coach ?? null,
        ]);
        return redirect()->route('profile.athlprofile', [
            'athlete_id' => $athlete->athlete_id
        ]);
    }
    
    public function removePage()
    {
        return view('athletes.remove');
    }

    public function destroyById(Request $request)
    {
        $athlete = Athlete::where('athlete_id', $request->athlete_id)->first();

        if (!$athlete) {
            return back()->with('success', 'Athlete not found.');
        }

        $athlete->delete();

        return back()->with('success', 'Athlete removed successfully.');
    }

    public function edit($athlete_id)
    {
        $athlete = Athlete::where('athlete_id', $athlete_id)->first();
        if (!$athlete) {
            return redirect()->route('profile.create')->with('error', 'Athlete profile not found.');
        }
        return view('profile.editprofile', compact('athlete'));
    }

    public function update(Request $request, $athlete_id)
    {
        $athlete = Athlete::where('athlete_id', $athlete_id)->first();
        if (!$athlete) {
            return redirect()->route('profile.create')->with('error', 'Athlete profile not found.');
        }

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
            'status' => $request->status, 
            'bmi' => round($bmi, 1),
        ]);

        // Update hydration settings for this athlete
        $hydrationSetting = \App\Models\HydrationSetting::where('athlete_id', $athlete->athlete_id)->first();
        if ($hydrationSetting) {
            $hydrationSetting->intensity = $request->intensity ?? $athlete->intensity;
            $hydrationSetting->save();
        }

        return redirect()->route('profile.athlprofile', ['athlete_id' => $athlete->athlete_id])->with('success', 'Profile updated successfully.');
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

    public function stayLoggedIn(Request $request)
    {
        $athlete = Athlete::where('email', Auth::user()->email)->first();
        if (!$athlete) {
            return response()->json(['success' => false, 'message' => 'Athlete profile not found.'], 404);
        }
        $athlete->stay_logged_in = $request->input('stay_logged_in', false);
        $athlete->save();
        return response()->json(['success' => true, 'stay_logged_in' => $athlete->stay_logged_in]);
    }

    public function show($athlete_id, Request $request)
    {
        $athlete = Athlete::where('athlete_id', $athlete_id)->first();
        // Only use query/request data if the athlete model is missing (e.g., just created and not yet saved)
        $data = [
            'athlete' => $athlete,
            'name' => $athlete?->name ?? $request->get('name'),
            'email' => $athlete?->email ?? $request->get('email'),
            'weight' => $athlete?->weight ?? $request->get('weight'),
            'height' => $athlete?->height ?? $request->get('height'),
            'bmi' => $athlete?->bmi ?? ((($athlete?->weight ?? $request->get('weight')) && ($athlete?->height ?? $request->get('height')))
                ? round(($athlete?->weight ?? $request->get('weight')) / pow(($athlete?->height ?? $request->get('height')) / 100, 2), 2)
                : null),
            'sport' => $athlete?->sport ?? $request->get('sport'),
            'training_intensity' => $athlete?->intensity ?? $request->get('training_intensity'),
        ];
        return view('profile.athlprofile', $data);
    }
}
