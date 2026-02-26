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
        // Generate unique coach_id (e.g. 6 random alphanumeric chars)
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
        return view('coach.edit', compact('coach'));
    }

    public function update(Request $request, $id)
    {
        $coach = Coach::findOrFail($id);
        $coach->update($request->all());

        return redirect()->route('coach.profile', $coach->id);
    }
}
