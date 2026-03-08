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

        // Try to get athlete by relationship, fallback to email
        $athlete = Auth::user()->athlete ?? \App\Models\Athlete::where('email', Auth::user()->email)->first();
        if (!$athlete) {
            return redirect()->back()->with('error', 'Athlete profile not found.');
        }

        $weight = $request->weight;
        $height = $request->height;
        $bmi = $height > 0 ? round($weight / pow($height / 100, 2), 2) : null;

        $athlete->update([
            'weight' => $weight,
            'height' => $height,
            'sport' => $request->sport,
            'intensity' => $request->intensity,
            'bmi' => $bmi,
        ]);

        return redirect()->route('profile.athlprofile');
    }
}
