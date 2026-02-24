<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HydrationReminderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

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

Route::post('/register', function () {
    // TODO: Add actual registration logic here
    return redirect()->route('home');
});

//Route::get('/home', function () {
  //  return view('home');
//})->name('home');

Route::get('/home', [HydrationReminderController::class, 'showHome'])->name('home');

Route::get('/training', function () {
    return view('training');
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
