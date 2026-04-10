<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Coach Profile</title>
    @vite('resources/css/create-profile.css')
</head>
<body>
    <main class="phone-shell" role="main" aria-label="Create coach profile form">
        <section class="content">
            <div class="logo-wrap" aria-hidden="true">
                <img
                    src="{{ asset('images/hydrapulse-logo.svg') }}"
                    alt="Hydrapulse logo"
                    onerror="this.style.display='none';"
                >
            </div>

            <h1>Create Profile</h1>
            <p class="lead">Complete your coach details to start.</p>

            @if ($errors->any())
                <div class="alert" role="alert" aria-live="polite">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('coach.store') }}">
                @csrf

                <input
                    class="field readonly"
                    type="text"
                    name="name"
                    value="{{ old('name', auth()->user() ? auth()->user()->name : '') }}"
                    readonly
                    aria-label="Name"
                >

                <input
                    class="field readonly"
                    type="email"
                    name="email"
                    value="{{ old('email', auth()->user() ? auth()->user()->email : '') }}"
                    readonly
                    aria-label="Email"
                >

                <select class="field" name="sport" required>
                    <option value="" disabled {{ old('sport') ? '' : 'selected' }}>Select Sport</option>
                    <option value="Running" {{ old('sport') === 'Running' ? 'selected' : '' }}>Running</option>
                    <option value="Badminton" {{ old('sport') === 'Badminton' ? 'selected' : '' }}>Badminton</option>
                    <option value="Swimming" {{ old('sport') === 'Swimming' ? 'selected' : '' }}>Swimming</option>
                    <option value="Volleyball" {{ old('sport') === 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                    <option value="Netball" {{ old('sport') === 'Netball' ? 'selected' : '' }}>Netball</option>
                </select>

                <input
                    class="field"
                    type="text"
                    name="phone_number"
                    value="{{ old('phone_number') }}"
                    placeholder="Phone Number"
                    required
                    aria-label="Phone Number"
                >

                <input
                    class="field"
                    type="text"
                    name="team_name"
                    value="{{ old('team_name') }}"
                    placeholder="Team Name"
                    aria-label="Team Name"
                >

                <button class="submit" type="submit">Proceed</button>
            </form>

            <p class="signin">
                Already have a profile? <a href="{{ route('login') }}">Sign In</a>
            </p>
        </section>
    </main>
</body>
</html>