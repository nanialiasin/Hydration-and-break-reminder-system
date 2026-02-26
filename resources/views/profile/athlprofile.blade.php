@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/athlprofile.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
<style>
    body, h1, h2, h3, p, span {
        font-family: 'Poppins', Arial, sans-serif !important;
    }
</style>
@section('content')
<div class="profile-page">
    <div class="profile-container">

        <div class="profile-title">Profile</div>

        <div class="profile-card">

            <div class="profile-avatar mb-2" style="margin-bottom: 18px;">
                <img src="{{ $athlete?->profile_pic && $athlete?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $athlete->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicView">
            </div>

            <div class="athlete-id">
                Athlete ID : {{ $athlete?->athlete_id ?? 'N/A' }}
            </div>

            <div class="profile-info">
                <p><strong>Name :</strong> {{ $athlete?->name ?? 'N/A' }}</p>
                <p><strong>Email :</strong> {{ $athlete?->email ?? 'N/A' }}</p>
                <p><strong>Weight (kg) :</strong> {{ $athlete?->weight ?? 'N/A' }}</p>
                <p><strong>Height (cm) :</strong> {{ $athlete?->height ?? 'N/A' }}</p>
                <p><strong>BMI :</strong> {{ $athlete?->bmi ?? 'N/A' }}</p>
                <p><strong>Sport :</strong> {{ $athlete?->sport ?? 'N/A' }}</p>
                <p><strong>Training Intensity :</strong> {{ $athlete?->intensity ?? 'N/A' }}</p>
                <p><strong>Status :</strong> <span class="status-active">Active</span></p>
                <div style="text-align:right;">
                    <a href="{{ route('profile.editprofile') }}" class="edit-profile-btn">
                        Edit
                    </a>
                </div>
            </div>
        </div>

        <div class="profile-card" style="display:flex; justify-content:space-between; align-items:center;">
            <span>Stay Logged In</span>
            <label class="switch">
                <input type="checkbox" {{ ($athlete?->stay_logged_in ?? false) ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>

        <div class="profile-card">
            <label>Volume for Alerts</label>
            <input type="range" min="0" max="100" value="{{ $athlete?->alert_volume ?? 50 }}">

            <label style="margin-top:15px;">Volume for Reminders</label>
            <input type="range" min="0" max="100" value="{{ $athlete?->reminder_volume ?? 50 }}">
        </div>

        <div class="button-group">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn-danger">Log Out</button>
            </form>

            <form method="POST" action="{{ route('profile.delete') }}">
                @csrf
                @method('DELETE')
                <button class="btn-danger">Delete Account</button>
            </form>
        </div>

    </div>
</div>
@endsection