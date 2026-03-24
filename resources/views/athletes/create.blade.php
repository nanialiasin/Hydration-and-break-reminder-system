<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Profile</title>
    @vite('resources/css/create-profile.css')
</head>
<body>
    <main class="phone-shell" role="main" aria-label="Create athlete profile form">
        <section class="content">
            <div class="logo-wrap" aria-hidden="true">
                <img
                    src="{{ asset('images/hydrapulse-logo.svg') }}"
                    alt="Hydrapulse logo"
                    onerror="this.style.display='none';"
                >
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