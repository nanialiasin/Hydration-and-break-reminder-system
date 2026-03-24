<!DOCTYPE html>
<html>
<head>
    <title>Athlete Activity</title>
    <link rel="stylesheet" href="{{ asset('css/creating.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <h1 class="page-title">Athlete Activity</h1>

            <section class="card">
                <h2 class="card-title">Team Overview</h2>
                <div class="stats">
                    @if($totalAthletes == 0 || $coachHasNotAddedAthletes)
                        <p class="empty-state">No athletes added yet.</p>
                    @else
                        <div class="stat-row">
                            <span>Active Athletes</span>
                            <strong>{{ $activeAthletes }}</strong>
                        </div>
                        <div class="stat-row">
                            <span>Inactive Athletes</span>
                            <strong>{{ $inactiveAthletes }}</strong>
                        </div>
                        <div class="stat-row">
                            <span>Total Athletes</span>
                            <strong>{{ $totalAthletes }}</strong>
                        </div>
                    @endif
                </div>

                <div class="intensity">
                    @if($averageIntensity)
                        <span>Average Training Intensity</span>
                        <strong>{{ ucfirst($averageIntensity) }}</strong>
                    @else
                        <span>Average Training Intensity</span>
                        <strong class="not-available">Not Available</strong>
                    @endif
                </div>

                <div class="actions">
                    <a href="{{ route('athletes.addathlete') }}" class="btn btn-add">Add Athlete</a>
                    <a href="{{ route('athletes.remove') }}" class="btn btn-remove">Remove Athlete</a>
                </div>
            </section>

            <section class="card session-card">
                <h2 class="card-title">Session</h2>
                @if($session && $session->sport)
                    <div class="stats">
                        <div class="stat-row">
                            <span>Sport</span>
                            <strong>{{ $session->sport }}</strong>
                        </div>
                        <div class="stat-row">
                            <span>Beginner</span>
                            <strong>{{ $session->beginner_duration ? sprintf('%d hr %d min', intdiv($session->beginner_duration, 60), $session->beginner_duration % 60) : 'Not Set' }}</strong>
                        </div>
                        <div class="stat-row">
                            <span>Intermediate</span>
                            <strong>{{ $session->intermediate_duration ? sprintf('%d hr %d min', intdiv($session->intermediate_duration, 60), $session->intermediate_duration % 60) : 'Not Set' }}</strong>
                        </div>
                        <div class="stat-row">
                            <span>Advanced</span>
                            <strong>{{ $session->advanced_duration ? sprintf('%d hr %d min', intdiv($session->advanced_duration, 60), $session->advanced_duration % 60) : 'Not Set' }}</strong>
                        </div>
                    </div>
                @else
                    <p class="empty-state">No session created yet.</p>
                @endif

                <div class="actions single-action">
                    <a href="{{ route('session.create') }}" class="btn btn-create">Create New Session</a>
                </div>
            </div>
        </div>
        <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="navi-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="navi-item active" aria-label="Create">
            <img src="{{ asset('images/Create.svg') }}" alt="Create" width="24" height="24">
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