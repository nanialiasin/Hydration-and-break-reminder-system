<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

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

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/history', function () {
    return view('history');
})->name('history');

Route::get('/session/create', function () {
    return view('create-session');
})->name('session.create');

Route::post('/session/store', function () {
    // TODO: Add actual session creation logic here
    return redirect()->route('session.show');
})->name('session.store');

Route::get('/session', function () {
    return view('session');
})->name('session.show');

Route::post('/session/end', function () {
    // TODO: Add session end logic (save to database)
    return redirect()->route('session.completed');
})->name('session.end');

Route::get('/session/completed', function () {
    return view('session-completed');
})->name('session.completed');
