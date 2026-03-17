<!DOCTYPE html>
<html>
<head>
    <title>Athlete Activity</title>
    <link rel="stylesheet" href="{{ asset('css/creating.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;}
    </style>
</head>
<body style="background: #e6f0fa; min-height: 100vh; margin: 0;">
    <div class="main-container">
        <h1 style="text-align:left; font-size:2.5rem; font-weight:bold; color:#222; letter-spacing:1px; margin-bottom:24px; margin-left:24px;">Athlete Activity</h1>
        <div class="card">
            <div class="card-title">Team Overview:</div>
            <hr style="border:none; border-top:1.5px solid #e0e7ef; margin-bottom:24px; margin-top:0;">
            <div class="stats">
                @if($totalAthletes == 0 || $coachHasNotAddedAthletes)
                    <p>No athletes added yet.</p>
                @else
                    <p>Active Athletes: <strong>{{ $activeAthletes }}</strong></p>
                    <p>Inactive Athletes: <strong>{{ $inactiveAthletes }}</strong></p>
                    <p>Total Athletes: <strong>{{ $totalAthletes }}</strong></p>
                @endif
            </div>
            <div class="intensity">
                @if($averageIntensity)
                    <span>Average Training Intensity: <span style="color: #0a4d68; font-weight:600;">{{ ucfirst($averageIntensity) }}</span></span>
                @else
                    <span>Average Training Intensity: <span style="color:rgb(0, 17, 255);">Not Available</span></span>
                @endif
            </div>
            <div class="actions">
                <a href="{{ route('athletes.addathlete') }}" class="btn btn-add">Add Athlete</a>
                <a href="{{ route('athletes.remove') }}" class="btn btn-remove">Remove Athlete</a>
            </div>
        </div>
        <div class="session-card">
            <div class="session-title">Session:</div>
            <hr style="border:none; border-top:1.5px solid #e0e7ef; margin-bottom:18px; margin-top:0;">
            @if($session && $session->sport)
                <div>
                    <p>Sport: <strong>{{ $session->sport }}</strong></p>
                    <p>Beginner: <strong>{{ $session->beginner_duration ? sprintf('%d hr %d min', intdiv($session->beginner_duration, 60), $session->beginner_duration % 60) : 'Not Set' }}</strong></p>
                    <p>Intermediate: <strong>{{ $session->intermediate_duration ? sprintf('%d hr %d min', intdiv($session->intermediate_duration, 60), $session->intermediate_duration % 60) : 'Not Set' }}</strong></p>
                    <p>Advanced: <strong>{{ $session->advanced_duration ? sprintf('%d hr %d min', intdiv($session->advanced_duration, 60), $session->advanced_duration % 60) : 'Not Set' }}</strong></p>
                </div>
            @else
                <p>No session created yet.</p>
            @endif
            <div class="actions">
                <a href="{{ route('session.create') }}" class="btn btn-create">Create New Session</a>
            </div>
        </div>
    </div>
    <nav class="nav-bar" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="navi-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="navi-item active" aria-label="Create">
            <img src="{{ asset('images/Create.svg') }}" alt="Create" width="24" height="24">
        </a>
        <a href="{{ route('coach.chistory') }}" class="navi-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</body>
</html>