<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Session History</title>
    @vite('resources/css/history.css')
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <header class="page-header">
                <a href="{{ route('coach.home') }}" class="back-btn">←</a>
                <h1 class="page-title">Session History</h1>
            </header>

        <div class="activities">
            @forelse(($sessions ?? collect()) as $session)
                <article class="activity-card">
            <div class="activity-header">
                <div class="activity-name">
                    <svg class="activity-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <path d="M8 12h8M12 8v8"></path>
                    </svg>
                    <span>{{ ucfirst($session->sport) }}</span>
                </div>
                <div class="activity-time">
                    <p class="date">{{ $session->date }}</p>
                    <p class="duration">{{ $session->duration }}</p>
                    </div>
            </div>
                <div class="hydration-section">
                    <span class="hydration-label">Team: {{ $session->team_name ?? 'N/A' }}</span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $session->completion_percentage ?? 0 }}%;"></div>
                    </div>
                </div>
            </article>
        @empty
            <article class="activity-card empty-history">
                <div class="activity-header">
                    <div class="activity-name">
                        <span>No sessions created yet</span>
                    </div>
                </div>
                    <div class="hydration-section">
                        <span class="hydration-label">Create a training session and it will appear here.</span>
                </div>
            </article>
        @endforelse
        </div>
    </div>
    
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
                <a href="{{ route('coach.chistory') }}" class="navi-item active" aria-label="History">
                    <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
                </a>
                <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
                    <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
                </a>
            </nav>
    </main>
</body>
</html>