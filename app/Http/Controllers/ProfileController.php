<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $athlete = Auth::athlete();
        Auth::logout();
        $athlete->delete();

        return redirect('/');
    }
}
