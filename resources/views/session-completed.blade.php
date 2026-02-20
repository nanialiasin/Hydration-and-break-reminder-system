<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Completed</title>
    @vite('resources/css/session-completed.css')
</head>
<body>
    <div class="app-shell">
        <div class="header">
            <h1>Session Completed!</h1>
        </div>

        <div class="content">
            <div class="results-card">
                <div class="stats-list">
                    <div class="stat-item">
                        <span class="stat-label">Duration</span>
                        <span class="stat-value">1hr 15min</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Alerts</span>
                        <span class="stat-value">6</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Followed</span>
                        <span class="stat-value">4</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Ignored</span>
                        <span class="stat-value">2</span>
                    </div>
                </div>

                <div class="hydration-score">
                    <svg class="score-ring" viewBox="0 0 200 200">
                        <!-- Background circle -->
                        <circle cx="100" cy="100" r="85" class="score-ring-bg"></circle>
                        <!-- Progress circle (88%) -->
                        <circle cx="100" cy="100" r="85" class="score-ring-progress"></circle>
                    </svg>
                    <div class="score-content">
                        <span class="score-label">Hydration<br>Score :</span>
                        <span class="score-percentage">88%</span>
                    </div>
                </div>

                <div class="session-actions">
                    <a href="{{ route('home') }}" class="btn btn-continue">Continue</a>
                </div>
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
            <a href="{{ route('history') }}" class="nav-item" aria-label="History">
                <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
            </a>
            <a href="#" class="nav-item" aria-label="Profile">
                <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
            </a>
        </nav>
    </div>
</body>
</html>
