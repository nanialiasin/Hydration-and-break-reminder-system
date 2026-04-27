<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use Illuminate\Http\Request;

class CoachProfileController extends Controller
{
    public function create()
    {
        return view('coach.createc');
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to create a coach profile.');
        }

        // Check if coach profile already exists for this user
        $existing = Coach::where('email', $user->email)->first();
        if ($existing) {
            // Redirect to existing profile
            return redirect()->route('coach.profile');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sport' => 'required|string',
            'phone_number' => 'required|string',
            'team_name' => 'required|string',
        ]);

        // Create coach profile with authenticated user's email
        $coach = Coach::create([
            'coach_id' => Coach::generateCoachId(),
            'name' => $validated['name'],
            'email' => $user->email,
            'sport' => $validated['sport'],
            'phone_number' => $validated['phone_number'],
            'team_name' => $validated['team_name'],
        ]);

        return redirect()->route('coach.profile');
    }

    public function show()
    {
        // Show profile for currently authenticated coach
        $user = auth()->user();
        if (!$user || !$user->email) {
            abort(404, 'Coach not found');
        }
        $coach = Coach::where('email', $user->email)->firstOrFail();
        return view('coach.coachprofile', compact('coach'));
    }

    public function edit($id)
    {
        $coach = Coach::findOrFail($id);
        return view('coach.editc', compact('coach'));
    }

    public function update(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);
        $coach->update($request->all());

        return redirect()->route('coach.profile');
    }

    public function updateStayLoggedIn(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);
        $coach->stay_logged_in = $request->has('stay_logged_in') ? 1 : 0;
        $coach->save();
        return redirect()->route('coach.profile')->with('success', 'Stay Logged In setting updated.');
    }

    public function updateProfilePic(Request $request, $id)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $coach = Coach::findOrFail($id);

        if ($request->hasFile('profile_pic')) {
            $profilePictureDisk = config('filesystems.profile_pictures_disk', 'public');
            $profilePicturePath = trim(config('filesystems.profile_pictures_path', 'profile_pics'), '/');
            $file = $request->file('profile_pic');
            $filename = uniqid('coach_') . '.' . $file->getClientOriginalExtension();

            $stored = \Storage::disk($profilePictureDisk)->putFileAs($profilePicturePath, $file, $filename);

            if (!$stored) {
                return back()->with('error', 'Failed to save profile picture.');
            }

            // Delete old profile pic if not default
            if ($coach->profile_pic && $coach->profile_pic !== 'default.jpg') {
                \Storage::disk($profilePictureDisk)->delete($profilePicturePath . '/' . $coach->profile_pic);
            }

            $coach->profile_pic = $filename;
            $coach->save();
        }

        return redirect()->route('coach.profile')->with('success', 'Profile picture updated!');
    }
    
    public function destroy($id)
    {
        $coach = Coach::findOrFail($id);
        $user = \App\Models\User::where('email', $coach->email)->first();

        // Logout before deletion
        \Auth::logout();

        // Delete the coach and all related data (cascades through model)
        $coach->delete();

        // Delete the associated user account
        if ($user) {
            $user->delete();
        }

        return redirect('/')->with('success', 'Coach account and all associated data have been permanently deleted.');
    }
}
