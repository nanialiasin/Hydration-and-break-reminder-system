<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History</title>
    @vite('resources/css/history.css')
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <header class="page-header">
                <a href="{{ route('home') }}" class="back-btn" aria-label="Back to home">←</a>
                <h1 class="page-title">History</h1>
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
                                <span>{{ ucfirst($session['sport']) }}</span>
                            </div>
                            <div class="activity-time">
                                <p class="date">{{ $session['date'] }}</p>
                                <p class="duration">{{ $session['duration'] }}</p>
                            </div>
                        </div>
                        <div class="hydration-section">
                            <span class="hydration-label">Hydration %</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ $session['hydration_score'] }}%;"></div>
                            </div>
                        </div>
                    </article>
                @empty
                    <article class="activity-card empty-history">
                        <div class="activity-header">
                            <div class="activity-name">
                                <span>No session history yet</span>
                            </div>
                        </div>
                        <div class="hydration-section">
                            <span class="hydration-label">Complete a session to see it here.</span>
                        </div>
                    </article>
                @endforelse
            </div>
        </div>

        <nav class="bottom-nav" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="nav-item" aria-label="Home">
                <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
            </a>
            <a href="#" class="nav-item" aria-label="Training">
                <img src="{{ asset('images/Training Button.svg') }}" alt="Training" width="24" height="24">
            </a>
            <a href="{{ route('session.create') }}" class="nav-item" aria-label="Create">
                <img src="{{ asset('images/Create.svg') }}" alt="Create" width="24" height="24">
            </a>
            <a href="{{ route('history') }}" class="nav-item active" aria-label="History">
                <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
            </a>
            <a href="#" class="nav-item" aria-label="Profile">
                <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
            </a>
        </nav>
    </main>
</body>
</html>
