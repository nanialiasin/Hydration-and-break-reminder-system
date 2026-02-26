<?php

namespace App\Http\Controllers;

use App\Models\AthleteProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AthleteProfileController extends Controller
{
    public function create()
    {
        return view('athlete.profile.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'sport' => 'required|string',
            'intensity' => 'required|string',
        ]);

        $athlete = Auth::user()->athlete;
        if (!$athlete) {
            return redirect()->back()->with('error', 'Athlete profile not found.');
        }

        AthleteProfile::updateOrCreate(
            ['athlete_id' => $athlete->id],
            [
                'weight' => $request->weight,
                'height' => $request->height,
                'sport' => $request->sport,
                'intensity' => $request->intensity,
            ]
        );

        return redirect()->route('athlete.home')->with('success', 'Profile updated successfully.');
    }
}
