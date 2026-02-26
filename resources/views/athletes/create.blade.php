@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/athleteprofile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/addathlete.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
    </style>
@section('content')
<div class="container">
    <a href="{{ url()->previous() }}" class="back-button">
    ←
    </a>

    <img src="{{ asset('images/hydrapulse-logo.png') }}" alt="Hydrapulse Logo" style="display:block; margin:0 auto 10px auto; width:260px; height:260px; border-radius:50%;">

    <div class="profile-card" style="margin-top: 0;">
        <h2>Create Profile</h2>

        <form method="POST" action="{{ route('profile.store') }}">
            @csrf

            <!-- Name (auto filled) -->
            <input type="text" 
                   value="{{ auth()->user() ? auth()->user()->name : '' }}" 
                   disabled class="input-field">

            <!-- Email (auto filled) -->
            <input type="email" 
                   value="{{ auth()->user() ? auth()->user()->email : '' }}" 
                   disabled class="input-field">

            <!-- Weight -->
            <label>Weight (kg)</label>
            <input type="number" name="weight" class="input-field" required>

            <!-- Height -->
            <label>Height (cm)</label>
            <input type="number" name="height" class="input-field" required>

            <!-- Sport -->
            <label>Sport</label>
            <select name="sport" class="input-field" required>
                <option value="">Select Sport</option>
                <option value="Running">Running</option>
                <option value="Badminton">Badminton</option>
                <option value="Swimming">Swimming</option>
                <option value="Volleyball">Volleyball</option>
                <option value="Netball">Netball</option>
            </select>

            <!-- Training Intensity -->
            <label>Training Intensity</label>
            <div class="radio-group">
                <label><input type="radio" name="training_intensity" value="Beginner"> Beginner</label>
                <label><input type="radio" name="training_intensity" value="Intermediate"> Intermediate</label>
                <label><input type="radio" name="training_intensity" value="Advanced"> Advanced</label>
            </div>

            <button type="submit" class="btn-submit">Proceed</button>
        </form>
    </div>
</div>
@endsection