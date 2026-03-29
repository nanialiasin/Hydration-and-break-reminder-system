<!DOCTYPE html>
<html>
<head>
    <title>Coach Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/coach-home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="greeting">Welcome, Coach.</h1>

        <section class="stats-card">
            <div class="stat-row">
                <span class="stat-label">Total Athletes</span>
                <span class="stat-value">{{ $totalAthletes ?? 0 }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Active Athletes</span>
                <span class="stat-value">{{ $activeAthletes ?? 0 }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Inactive Athletes</span>
                <span class="stat-value">{{ $inactiveAthletes ?? 0 }}</span>
            </div>
        </section>

        <div class="training-card">
            Today's Training Time :
        </div>

        <div class="card">
            <div class="card-title">Team Overview :</div>
            <p>Total Athletes : {{ $totalAthletes }}</p>
            <p>Checked-in : {{ $checkedIn }}</p>
            <p>Not Checked-in : {{ $notCheckedIn }}</p>
            <hr>
            <div style="margin-top:10px;">
                <strong>Team Members:</strong>
                <ul style="padding-left:18px;">
                    @forelse($athletes as $athlete)
                        <li>
                            {{ $athlete->name }} (ID: {{ $athlete->athlete_id }}) - {{ $athlete->sport }} | Status: {{ $athlete->status }}
                        </li>
                    @empty
                        <li>No athletes in your team yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Recent Activities :</div>
            <p>No recent activity.</p>
        </div>

        <section class="actions-card">
            <h2 class="card-title">Quick Actions</h2>
            <div class="action-buttons">
                <a href="{{ route('athletes.addathlete') }}" class="action-btn">Add Athlete</a>
                <a href="{{ route('session.create') }}" class="action-btn">Create Session</a>
            </div>
        </section>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="nav-item active" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="nav-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="nav-item" aria-label="Activity">
            <img src="{{ asset('images/Create.svg') }}" alt="Activity" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="nav-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="nav-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
<<<<<<< HEAD
<nav class="nav-bar" aria-label="Main navigation" style="position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);z-index:1000;display:flex;justify-content:space-between;align-items:center;background:#000;border-radius:16px 16px 0 0;box-shadow:0 -2px 12px rgba(0,0,0,0.08);padding:10px 32px 8px 32px;max-width:100vw;border-top:1.5px solid #e0e7ef;margin:0;">
    <a href="{{ route('coach.home') }}" class="navi-item active" aria-label="Home">
        <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
    </a>
    <a href="{{ route('hydration.index') }}" class="navi-item" aria-label="Hydration">
        <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
    </a>
    <a href="{{ route('coach.creating') }}" class="navi-item" aria-label="Create">
        <img src="{{ asset('images/Create.svg') }}" alt="Create" width="24" height="24">
    </a>
    <a href="{{ route('coach.chistory') }}" class="navi-item" aria-label="History">
        <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
    </a>
    <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
        <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
    </a>
</nav>
=======
>>>>>>> 6cb677dfe69db418dc3fc21e02e3bea54b4cfedc
</body>
</html>