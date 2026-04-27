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
    <img src="{{ asset('images/hydrapulse-logo.svg') }}" alt="Hydrapulse Logo" style="display:block; margin:0 auto 10px auto; width:120px; height:120px; border-radius:50%;">

    <div class="profile-card" style="margin-top: 0;">
        <h2>Create Profile</h2>
        <p class="lead">Complete your athlete details to start.</p>
        @if($errors->any())
            <div class="alert alert-danger" style="margin-bottom:16px;">
                <ul style="margin:0; padding-left:18px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('athletes.store') }}">
            @csrf
            <!-- Name (readonly, styled) -->
            <div class="input-field readonly-field" style="width:90%; min-height:33px; background:#e7f2ff; border-radius:12px; box-shadow:0 2px 8px rgba(30,58,47,0.06); display:flex; align-items:center; padding:0 18px; margin-bottom:12px; font-size:1.1rem;">{{ Auth::user()->name ?? '' }}</div>
            <input type="hidden" name="name" value="{{ Auth::user()->name ?? '' }}">
            <!-- Email (readonly, styled) -->
            <div class="input-field readonly-field" style="width:90%; min-height:33px; background:#e7f2ff; border-radius:12px; box-shadow:0 2px 8px rgba(30,58,47,0.06); display:flex; align-items:center; padding:0 18px; margin-bottom:12px; font-size:1.1rem;">{{ Auth::user()->email ?? '' }}</div>
            <input type="hidden" name="email" value="{{ Auth::user()->email ?? '' }}">
            <!-- Weight -->
            <label>Weight (kg)</label>
            <input type="number" name="weight" class="input-field" required value="{{ old('weight', isset($weight) ? $weight : '') }}">
            <!-- Height -->
            <label>Height (cm)</label>
            <input type="number" name="height" class="input-field" required value="{{ old('height', isset($height) ? $height : '') }}">
            <!-- Sport -->
            <label>Sport</label>
            <select name="sport" class="input-field" required>
                <option value="">Select Sport</option>
                <option value="Running" {{ old('sport', isset($sport) ? $sport : '') == 'Running' ? 'selected' : '' }}>Running</option>
                <option value="Badminton" {{ old('sport', isset($sport) ? $sport : '') == 'Badminton' ? 'selected' : '' }}>Badminton</option>
                <option value="Swimming" {{ old('sport', isset($sport) ? $sport : '') == 'Swimming' ? 'selected' : '' }}>Swimming</option>
                <option value="Volleyball" {{ old('sport', isset($sport) ? $sport : '') == 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                <option value="Netball" {{ old('sport', isset($sport) ? $sport : '') == 'Netball' ? 'selected' : '' }}>Netball</option>
            </select>
            <!-- Training Intensity -->
            <label>Training Intensity</label>
            <div class="radio-group">
                <label><input type="radio" name="training_intensity" value="Beginner" {{ old('training_intensity', isset($training_intensity) ? $training_intensity : '') == 'Beginner' ? 'checked' : '' }}> Beginner</label>
                <label><input type="radio" name="training_intensity" value="Intermediate" {{ old('training_intensity', isset($training_intensity) ? $training_intensity : '') == 'Intermediate' ? 'checked' : '' }}> Intermediate</label>
                <label><input type="radio" name="training_intensity" value="Advanced" {{ old('training_intensity', isset($training_intensity) ? $training_intensity : '') == 'Advanced' ? 'checked' : '' }}> Advanced</label>
            </div>
            <button type="submit" class="btn-submit">Proceed</button>
        </form>
    </div>
</div>
@endsection