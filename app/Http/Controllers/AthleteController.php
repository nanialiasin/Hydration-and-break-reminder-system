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
        return view('athletes.create', compact('name', 'email'));
    }

    public function fetch($athlete_id)
    {
        $athlete = \App\Models\Athlete::where('athlete_id', $athlete_id)->first();
        if ($athlete) {
            return response()->json([
                'name' => $athlete->name,
                'sport' => $athlete->sport,
                'intensity' => $athlete->intensity,
                'training_intensity' => $athlete->intensity,
                'status' => $athlete->status,
            ]);
        }
        return response()->json(null, 404);
    }

    public function addById(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in as a coach.');
        }

        $validated = $request->validate([
            'athlete_id' => 'required|string',
        ]);

        $athlete = Athlete::where('athlete_id', $validated['athlete_id'])->first();

        if (!$athlete) {
            return back()->with('error', 'Athlete ID not found.');
        }

        $coachId = (string) $user->id;
        if ((string) $athlete->created_by_coach === $coachId) {
            return back()->with('success', 'Athlete is already in your team.');
        }

        $athlete->created_by_coach = $coachId;
        $athlete->save();

        return back()->with('success', 'Athlete added to your team successfully.');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to create an athlete profile.');
        }

        $validated = $request->validate([
            'name' => 'required',
            'sport' => 'required',
            'weight' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
            'training_intensity' => 'required',
        ]);
        
        // Calculate BMI
        $bmi = round($validated['weight'] / pow($validated['height'] / 100, 2), 2);

        // Create a new athlete profile or update the existing one for this email.
        $athlete = Athlete::firstOrNew(['email' => $user->email]);

        if (empty($athlete->athlete_id)) {
            $athlete->athlete_id = Athlete::generateAthleteId();
        }

        $athlete->name = $validated['name'];
        $athlete->email = $user->email;
        $athlete->sport = $validated['sport'];
        $athlete->weight = (float) $validated['weight'];
        $athlete->height = (float) $validated['height'];
        $athlete->bmi = $bmi;
        $athlete->intensity = $validated['training_intensity'];
        $athlete->status = $athlete->status ?: 'active';
        $athlete->save();
        
        // Refresh the athlete to ensure all data is loaded
        $athlete->refresh();
        
        return redirect()->route('profile.athlprofile', [
            'athlete_id' => $athlete->athlete_id,
            'name' => $athlete->name,
            'email' => $athlete->email,
            'weight' => $athlete->weight,
            'height' => $athlete->height,
            'sport' => $athlete->sport,
            'training_intensity' => $athlete->intensity
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
            return back()->with('error', 'Athlete not found.');
        }

        // Delete athlete and all related data (cascades through model)
        $athlete->delete();

        return back()->with('success', 'Athlete and all associated data removed successfully.');
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
        
        if (!$athlete) {
            return redirect()->route('athletes.create')->with('error', 'Athlete profile not found.');
        }
        
        // Use athlete data directly - no need for query param fallback
        // since data should be persisted in database
        $weight = $athlete->weight;
        $height = $athlete->height;
        $bmi = $athlete->bmi;
        
        // Calculate BMI if missing but weight/height exist
        if (!$bmi && $weight && $height) {
            $bmi = round($weight / pow($height / 100, 2), 2);
        }
        
        $data = [
            'athlete' => $athlete,
            'name' => $athlete->name,
            'email' => $athlete->email,
            'weight' => $weight,
            'height' => $height,
            'bmi' => $bmi,
            'sport' => $athlete->sport,
            'training_intensity' => $athlete->intensity,
        ];
        return view('profile.athlprofile', $data);
    }
}
