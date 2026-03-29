<!DOCTYPE html>
<html>
<head>
    <title>Coach Sessions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/coach-in-progress.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="page-title">Coach Sessions</h1>

        <section class="list-wrap" aria-label="In progress sessions">
            @forelse(($sessions ?? collect()) as $session)
                <article class="session-card">
                    <div class="row">
                        <span class="label">Status</span>
                        <span class="value">{{ $session->started_at ? 'In Progress' : 'Pending' }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Athlete</span>
                        <span class="value">{{ $athleteNames[$session->athlete_id] ?? $session->athlete_id ?? 'Unknown' }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Sport</span>
                        <span class="value">{{ ucfirst($session->sport ?? 'General Training') }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Intensity</span>
                        <span class="value">{{ ucfirst($session->intensity ?? 'Beginner') }}</span>
                    </div>
                    <div class="row">
                        <span class="label">Planned</span>
                        <span class="value">{{ (int)($session->planned_duration_minutes ?? 0) }} min</span>
                    </div>
                    <div class="row">
                        <span class="label">Started</span>
                        <span class="value">{{ optional($session->started_at)->diffForHumans() ?? 'Not started yet' }}</span>
                    </div>
                </article>
            @empty
                <article class="session-card empty">
                    <p>No pending or in-progress sessions right now.</p>
                </article>
            @endforelse
        </section>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="navi-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="navi-item" aria-label="Activity">
            <img src="{{ asset('images/Create.svg') }}" alt="Activity" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="navi-item active" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
</body>
</html>
