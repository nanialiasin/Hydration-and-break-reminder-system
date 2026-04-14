<!DOCTYPE html>
<html>
<head>
    <title>Hydration Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@php
    $selectedPlan = $selectedSetting ?? $settings->first();
@endphp
<main role="main" class="app-shell">
    <div class="content">
        <header class="card app-header">
            <div>
                <p class="eyebrow">Coach dashboard</p>
                <h1 class="page-title">Hydration Settings</h1>
            </div>

            <div class="header-tools">
                @if($athletes && $athletes->count() > 1)
                    <form method="GET" action="{{ route('hydration.index') }}" class="athlete-switcher">
                        <label class="field-label" for="athlete_switcher">Athlete</label>
                        <select id="athlete_switcher" name="athlete_id" class="field-input" onchange="this.form.submit()">
                            @foreach($athletes as $item)
                                <option value="{{ $item->athlete_id }}" {{ $selectedAthleteId === $item->athlete_id ? 'selected' : '' }}>
                                    {{ $item->name }} • {{ $item->sport ?? 'No sport' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <a href="{{ route('hydration.edit', ['athlete_id' => $selectedAthleteId]) }}" class="btn btn-edit app-button">Open editor</a>
            </div>
        </header>

        <section class="card athlete-card">
            <div class="athlete-top">
                <div class="athlete-headline">
                    <p class="card-kicker">Selected athlete</p>
                    <div class="athlete-name-row">
                        <h2 class="card-title">{{ $athlete?->name ?? 'No athlete selected' }}</h2>
                        <span class="setting-pill athlete-id-pill">{{ $athlete?->athlete_id ?? 'No profile' }}</span>
                    </div>
                </div>
            </div>

            <div class="tag-row">
                <span class="tag">{{ $athlete?->weight ? $athlete->weight . ' kg' : 'N/A' }}</span>
                <span class="tag">{{ $athlete?->height ? $athlete->height . ' cm' : 'N/A' }}</span>
                <span class="tag">{{ $athlete?->bmi ?? 'N/A' }}</span>
                <span class="tag">{{ $athlete?->sport ?? 'Sport not set' }}</span>
                <span class="tag">{{ $athlete?->intensity ? ucfirst($athlete->intensity) : 'Intensity not set' }}</span>
                <span class="tag">{{ $athlete?->status ? ucfirst($athlete->status) : 'Active' }}</span>
            </div>
        </section>

        <section class="metric-grid">
            <article class="metric-card">
                <span class="metric-label">Weight</span>
                <strong>{{ $athlete?->weight ? $athlete->weight . ' kg' : 'N/A' }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Height</span>
                <strong>{{ $athlete?->height ? $athlete->height . ' cm' : 'N/A' }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">BMI</span>
                <strong>{{ $athlete?->bmi ?? 'N/A' }}</strong>
            </article>
            <article class="metric-card">
                <span class="metric-label">Plan</span>
                <strong>{{ $selectedPlan?->intensity ? ucfirst($selectedPlan->intensity) : 'Default' }}</strong>
            </article>
        </section>

        <section class="card plan-card">
            <div class="card-heading">
                <div>
                    <p class="card-kicker">Plan cards</p>
                    <h2 class="card-title">Settings by intensity</h2>
                </div>
                <span class="card-badge">{{ $selectedPlan?->intensity ? ucfirst($selectedPlan->intensity) : 'Beginner' }} selected</span>
            </div>

            <p class="plan-note">Base reminder before weight and weather adjustments.</p>

            <div class="settings-grid app-grid">
                @foreach($settings as $setting)
                    <article class="setting-item {{ $selectedPlan && $selectedPlan->id === $setting->id ? 'selected' : '' }}">
                        <div class="setting-header">
                            <div>
                                <div class="setting-intensity">{{ ucfirst($setting->intensity) }}</div>
                            </div>
                            <span class="setting-pill">{{ $setting->hydration_reminder }} min</span>
                        </div>
                        <div class="setting-details">
                            <div class="setting-detail">
                                <span>Hydration reminder</span>
                                <span class="setting-value">{{ $setting->hydration_reminder }}m</span>
                            </div>
                            <div class="setting-detail">
                                <span>Break duration</span>
                                <span class="setting-value">{{ $setting->break_duration }}m</span>
                            </div>
                            <div class="setting-detail">
                                <span>Break reminder</span>
                                <span class="setting-value">{{ $setting->break_reminder }}m</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
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