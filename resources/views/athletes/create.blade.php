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

    <img src="{{ asset('images/hydrapulse-logo.svg') }}" alt="Hydrapulse Logo" style="display:block; margin:0 auto 10px auto; width:260px; height:260px; border-radius:50%;">

    <div class="profile-card" style="margin-top: 0;">
        <h2>Create Profile</h2>

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

            <!-- Name (auto filled) -->
            <input type="text" 
                   name="name"
                   value="{{ old('name', isset($name) ? $name : (auth()->user() ? auth()->user()->name : '')) }}" 
                   readonly class="input-field">

            <!-- Email (auto filled) -->
            <input type="email" 
                   name="email"
                   value="{{ old('email', isset($email) ? $email : (auth()->user() ? auth()->user()->email : '')) }}" 
                   readonly class="input-field">

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

            <h1>Create Profile</h1>
            <p class="lead">Complete your athlete details to start.</p>

            @if ($errors->any())
                <div class="alert" role="alert" aria-live="polite">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('athletes.store') }}">
                @csrf

                <input
                    class="field readonly"
                    type="text"
                    name="name"
                    value="{{ old('name', isset($name) ? $name : (auth()->user() ? auth()->user()->name : '')) }}"
                    readonly
                    aria-label="Name"
                >

                <input
                    class="field readonly"
                    type="email"
                    name="email"
                    value="{{ old('email', isset($email) ? $email : (auth()->user() ? auth()->user()->email : '')) }}"
                    readonly
                    aria-label="Email"
                >

                <input
                    class="field"
                    type="number"
                    name="weight"
                    value="{{ old('weight') }}"
                    placeholder="Weight (kg)"
                    min="1"
                    step="0.1"
                    required
                >

                <input
                    class="field"
                    type="number"
                    name="height"
                    value="{{ old('height') }}"
                    placeholder="Height (cm)"
                    min="1"
                    step="0.1"
                    required
                >

                <select class="field" name="sport" required>
                    <option value="" disabled {{ old('sport') ? '' : 'selected' }}>Select Sport</option>
                    <option value="Running" {{ old('sport') === 'Running' ? 'selected' : '' }}>Running</option>
                    <option value="Badminton" {{ old('sport') === 'Badminton' ? 'selected' : '' }}>Badminton</option>
                    <option value="Swimming" {{ old('sport') === 'Swimming' ? 'selected' : '' }}>Swimming</option>
                    <option value="Volleyball" {{ old('sport') === 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                    <option value="Netball" {{ old('sport') === 'Netball' ? 'selected' : '' }}>Netball</option>
                </select>

                <p class="intensity-label">Training Intensity:</p>
                <div class="roles" role="radiogroup" aria-label="Training intensity">
                    <label><input type="radio" name="training_intensity" value="Beginner" {{ old('training_intensity') === 'Beginner' ? 'checked' : '' }} required> Beginner</label>
                    <label><input type="radio" name="training_intensity" value="Intermediate" {{ old('training_intensity') === 'Intermediate' ? 'checked' : '' }} required> Intermediate</label>
                    <label><input type="radio" name="training_intensity" value="Advanced" {{ old('training_intensity') === 'Advanced' ? 'checked' : '' }} required> Advanced</label>
                </div>

                <button class="submit" type="submit">Proceed</button>
            </form>

            <p class="signin">
                Already have a profile? <a href="{{ route('login') }}">Sign In</a>
            </p>
        </section>
    </main>
</body>
</html>