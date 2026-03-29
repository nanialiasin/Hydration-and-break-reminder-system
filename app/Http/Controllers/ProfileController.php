<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Athlete;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index', [
            'athlete' => Auth::athlete()
        ]);
    }

    public function update(Request $request)
    {
        $athlete = Auth::athlete();

        $athlete->update($request->all());

        return back()->with('success', 'Profile updated!');
    }

    public function destroy()
    {
        $user = Auth::user();
        $athlete = Athlete::where('email', $user->email)->first();

        // Logout before deletion
        Auth::logout();

        // Delete athlete and all related data (cascades through model)
        if ($athlete) {
            $athlete->delete();
        }

        // Delete the user account
        if ($user) {
            $user->delete();
        }

        return redirect('/')->with('success', 'Account and all associated data have been permanently deleted.');
    }
}
