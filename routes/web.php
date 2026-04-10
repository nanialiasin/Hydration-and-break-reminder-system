<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\HydrationReminderController;
use App\Http\Controllers\CoachDashboardController;
use App\Http\Controllers\AthleteController;
use App\Http\Controllers\HydrationSettingController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AthleteProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CoachProfileController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        if ($user->role === 'coach') {
            return redirect()->route('coach.profile');
        } elseif ($user->role === 'athlete') {
            // Find athlete profile for this user
            $athlete = \App\Models\Athlete::where('email', $user->email)->first();
            if ($athlete && $athlete->athlete_id) {
                return redirect()->route('profile.athlprofile', ['athlete_id' => $athlete->athlete_id]);
            } else {
                // If athlete profile no longer exists, invalidate this auth account
                // so the same email must sign up again before using the app.
                $email = $user->email;
                $name = $user->name;
                $userId = $user->id;

                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                \App\Models\User::where('id', $userId)->delete();

                return redirect()->route('register')
                    ->withErrors(['email' => 'This athlete account no longer exists. Please sign up again.'])
                    ->withInput([
                        'name' => $name,
                        'email' => $email,
                    ]);
            }
        } else {
            return redirect('/home');
        }
    }
    return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
});

Route::get('/streak', [HydrationReminderController::class, 'showStreak'])
    ->name('streak');

Route::get('/forgot-password', function () {
    return view('forgot-password');
})->name('password.request');

Route::post('/forgot-password', function () {
    // TODO: Add email sending logic here
    return redirect()->route('password.reset');
})->name('password.email');

Route::get('/reset-password', function () {
    return view('reset-password');
})->name('password.reset');

Route::post('/reset-password', function () {
    // TODO: Add password update logic here
    return redirect()->route('login');
})->name('password.reset');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::post('/register', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:coach,athlete',
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role, // Save role
    ]);

    \Auth::login($user);

    \Log::info('Registration successful for role: ' . $request->role);
    \Log::info('Redirecting to: ' . ($request->role === 'athlete' ? 'athletes.create' : 'coach.createc'));
    \Log::info('Is authenticated after login: ' . (\Auth::check() ? 'yes' : 'no'));

    if ($request->role === 'athlete') {
        $athleteId = uniqid('ATH');
        \App\Models\Athlete::create([
            'athlete_id' => $athleteId,
            'name' => $user->name,
            'email' => $user->email,
            'sport' => '',
            'status' => 'active',
            'intensity' => '',
        ]);
        // Pass name and email to the view
        return redirect()->route('athletes.create', [
            'name' => $user->name,
            'email' => $user->email
        ]);
    } else {
        return redirect()->route('coach.createc');
    }
});

Route::get('/home', function () {
    $athlete = \App\Models\Athlete::where('email', Auth::user()->email)->first();
    return view('home', compact('athlete'));
})->name('home');

Route::get('/training', function () {
    $athlete = \App\Models\Athlete::where('email', Auth::user()->email)->first();

    $todoSessions = collect();
    if ($athlete) {
        $todoSessions = \App\Models\HydrationSession::query()
            ->where('assigned_by_coach', true)
            ->where('athlete_id', $athlete->athlete_id)
            ->whereNull('completed_at')
            ->latest('id')
            ->get();
    }

    return view('training', compact('athlete', 'todoSessions'));
})->name('training');

Route::get('/history', [HydrationReminderController::class, 'showHistory'])->name('history');

Route::get('/session/create', function () {
    return view('create-session');
})->name('session.create');

Route::get('/hydration/alert', function () {
    return view('hydration-alert');
})->name('hydration.alert');

Route::get('/session', function () {
    return view('session');
})->name('session.show');

Route::get('/session/completed', [HydrationReminderController::class, 'showSessionCompleted'])
    ->name('session.completed');

Route::post('/session/store', [HydrationReminderController::class, 'startSession'])
    ->name('session.store');

Route::post('/session/end', [HydrationReminderController::class, 'endSession'])
    ->name('session.end');

Route::post('/hydration/calculate', [HydrationReminderController::class, 'getReminderStatus']);

Route::get('/coach/creating', [CoachDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('coach.creating');

Route::get('/athletes/fetch/{athlete_id}', [AthleteController::class, 'fetch'])->name('athletes.fetch');

Route:: get('/athletes', [AthleteController::class, 'create'])->name('athletes.create');
Route::post('/athletes', [AthleteController::class, 'store'])->name('athletes.store');

Route::get('/athletes/addathlete', [AthleteController::class, 'addAthleteShowPage'])->name('athletes.addathlete');
Route::post('/athletes/addathlete', [AthleteController::class, 'addById'])->name('athletes.add.byid');

Route::get('/athletes/remove', [AthleteController::class, 'removePage'])->name('athletes.remove');
Route::delete('/athletes/remove', [AthleteController::class, 'destroyById'])->name('athletes.destroy.byid');

Route::get('/hydration', [HydrationSettingController::class, 'index'])->name('hydration.index');
Route::get('/hydration/edit', [HydrationSettingController::class, 'edit'])->name('hydration.edit');
Route::post('/hydration/update', [HydrationSettingController::class, 'update'])->name('hydration.update');

Route::get('/coach/home', [CoachController::class, 'index'])->name('coach.home');
Route::get('/coach/sessions/in-progress', [CoachController::class, 'inProgressSessions'])
    ->middleware('auth')
    ->name('coach.sessions.progress');

Route::get('/athlprofile/{athlete_id}', [AthleteController::class, 'show'])->name('profile.athlprofile');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile/create', [AthleteProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile/store', [AthleteProfileController::class, 'store'])->name('profile.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');
});

// Show edit form
Route::get('/athlete/profile/edit', [AthleteController::class, 'edit'])
    ->name('athlprofile.edit');

// Update profile
Route::put('/athlete/profile/update', [AthleteController::class, 'update'])
    ->name('athlprofile.update');

Route::put('/profile/update/{athlete_id}', [AthleteController::class, 'update'])->name('athlete.profile.update');

Route::get('/profile/editprofile/{athlete_id}', [AthleteController::class, 'edit'])->name('profile.editprofile');

Route::post('/profile/update-pic', [AthleteController::class, 'updateProfilePic'])->name('profile.updatePic');

Route::post('/athlete/stayloggedin', [AthleteController::class, 'stayLoggedIn'])->name('athlete.stayloggedin');

Route::post('/athlete/profile/store', [App\Http\Controllers\AthleteProfileController::class, 'store'])->name('athlete.profile.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/coach/create', [CoachProfileController::class, 'create'])->name('coach.createc');
    Route::post('/coach/store', [CoachProfileController::class, 'store'])->name('coach.store');
    Route::get('/coach/profile', [CoachProfileController::class, 'show'])->name('coach.profile');
    Route::get('/coach/edit/{id}', [CoachProfileController::class, 'edit'])->name('coach.edit');
    Route::post('/coach/profile/update/{id}', [CoachProfileController::class, 'update'])->name('coach.update');
    Route::delete('/coach/delete/{id}', [CoachProfileController::class, 'destroy'])->name('coach.delete');
    Route::post('/coach/stayloggedin/{id}', [CoachProfileController::class, 'updateStayLoggedIn'])->name('coach.stayloggedin');
    Route::post('/coach/update-pic/{id}', [CoachProfileController::class, 'updateProfilePic'])->name('coach.updatePic');
    Route::post('/coach/addathlete', [App\Http\Controllers\CoachDashboardController::class, 'storeAthlete'])
        ->name('coach.addathlete.store');
});

Route::get('/coach/history', [CoachController::class, 'history'])->name('coach.chistory');

Route::delete('/profile/delete', function () {
    $user = Auth::user();
    if ($user) {
        // Delete athlete profile if exists
        $athlete = \App\Models\Athlete::where('email', $user->email)->first();
        if ($athlete) {
            if (method_exists($athlete, 'forceDelete')) {
                $athlete->forceDelete();
            } else {
                $athlete->delete();
            }
        }
        $user->delete();
        Auth::logout();
        return redirect('/login')->with('status', 'Account deleted successfully.');
    }
    return redirect('/login')->with('error', 'No user found.');
})->name('profile.delete');

Route::get('/profile/delete/confirm', function () {
    return view('profile.delete');
})->name('profile.delete.confirm');

Route::match(['get', 'post'], '/sensor/ingest', [HydrationReminderController::class, 'ingestSensorReading'])
    ->name('sensor.ingest');

Route::get('/sensor/latest', [HydrationReminderController::class, 'latestSensorReading'])
    ->name('sensor.latest');
