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

    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="back-button">
        ←
    </a>

    <!-- Large Center Logo -->
    <img src="{{ asset('images/hydrapulse-logo.png') }}" 
         alt="Hydrapulse Logo" 
         style="display:block; margin:0 auto 10px auto; width:260px; height:260px; border-radius:50%;">

    <div class="profile-card" style="margin-top: 0;">
        <h2>Create Profile</h2>

        <form action="{{ route('coach.store') }}" method="POST">
            @csrf

            <!-- Coach Name -->
            <div class="input-field readonly-field" style="width:90%; min-height:33px; background:#e7f2ff; border-radius:12px; box-shadow:0 2px 8px rgba(30,58,47,0.06); display:flex; align-items:center; padding:0 18px; margin-bottom:12px; font-size:1.1rem;">{{ Auth::user()->name ?? '' }}</div>
            <input type="hidden" name="name" value="{{ Auth::user()->name ?? '' }}">

            <!-- Email -->
            <div class="input-field readonly-field" style="width:90%; min-height:33px; background:#e7f2ff; border-radius:12px; box-shadow:0 2px 8px rgba(30,58,47,0.06); display:flex; align-items:center; padding:0 18px; margin-bottom:12px; font-size:1.1rem;">{{ Auth::user()->email ?? '' }}</div>
            <input type="hidden" name="email" value="{{ Auth::user()->email ?? '' }}">

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

            <!-- Phone -->
            <label>Phone Number</label>
            <input type="text" 
                   name="phone_number" 
                   placeholder="Enter phone number" 
                   class="input-field" required>

            <!-- Team Name -->
            <label>Team Name (Optional)</label>
            <input type="text" 
                   name="team_name" 
                   placeholder="Enter team name" 
                   class="input-field">

            <button type="submit" class="btn-submit">
                Proceed
            </button>
        </form>
    </div>

</div>
@endsection