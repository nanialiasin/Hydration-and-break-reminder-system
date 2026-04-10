<!DOCTYPE html>
<html>
<head>
    <title>Edit Hydration Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main role="main" class="app-shell">
    <div class="content">
        <div class="top-row">
            <a href="{{ route('hydration.index') }}" class="back-link" aria-label="Back to hydration settings">&#8592;</a>
            <h1 class="page-title page-title-compact">Edit Hydration Settings</h1>
        </div>

        <section class="card form-card">
            <form action="{{ route('hydration.update') }}" method="POST" class="settings-form">
                @csrf

                @foreach($settings as $setting)
                    <div class="setting-form-block">
                        <h2 class="setting-intensity">{{ ucfirst($setting->intensity ?? $setting->level) }}</h2>

                        <div class="field-row">
                            <label class="field-label" for="hydration_reminder_{{ $setting->id }}">Hydration reminder every (min)</label>
                            <input
                                id="hydration_reminder_{{ $setting->id }}"
                                type="number"
                                min="1"
                                name="settings[{{ $setting->id }}][hydration_reminder]"
                                value="{{ $setting->hydration_reminder }}"
                                class="field-input"
                                required
                            >
                        </div>

                        <div class="field-row">
                            <label class="field-label" for="break_duration_{{ $setting->id }}">Break duration (min)</label>
                            <input
                                id="break_duration_{{ $setting->id }}"
                                type="number"
                                min="1"
                                name="settings[{{ $setting->id }}][break_duration]"
                                value="{{ $setting->break_duration }}"
                                class="field-input"
                                required
                            >
                        </div>

                        <div class="field-row">
                            <label class="field-label" for="break_reminder_{{ $setting->id }}">Break reminder every (min)</label>
                            <input
                                id="break_reminder_{{ $setting->id }}"
                                type="number"
                                min="1"
                                name="settings[{{ $setting->id }}][break_reminder]"
                                value="{{ $setting->break_reminder }}"
                                class="field-input"
                                required
                            >
                        </div>
                    </div>

                    @if(!$loop->last)
                        <hr class="setting-divider">
                    @endif
                @endforeach

                <div class="form-actions">
                    <a href="{{ route('hydration.index') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-save">Save Changes</button>
                </div>
            </form>
        </section>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="navi-item active" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="navi-item" aria-label="Activity">
            <img src="{{ asset('images/Create.svg') }}" alt="Activity" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="navi-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
</body>
</html>