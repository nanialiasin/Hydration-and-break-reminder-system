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
        $uniqueId = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
        $coachData = $request->all();
        $coachData['coach_id'] = $uniqueId;
        $coach = Coach::create($coachData);
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
        $coach = Coach::findOrFail($id);
        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $filename = uniqid('coach_') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/profile_pics', $filename);
            $coach->profile_pic = $filename;
            $coach->save();
        }
        return redirect()->route('coach.edit', $coach->id)->with('success', 'Profile picture updated!');
    }

    public function destroy($id)
    {
        $coach = Coach::findOrFail($id);
        // Optionally, also delete the user record if linked
        // $user = User::where('email', $coach->email)->first();
        // if ($user) $user->delete();
        $coach->delete();
        \Auth::logout();
        return redirect('/')->with('success', 'Coach account deleted successfully.');
    }
}
