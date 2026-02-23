<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Completed</title>
    @vite('resources/css/session-completed.css')
</head>
<body>
    @php
        $score = max(0, min(100, (int) ($hydrationScore ?? 0)));
        $circumference = 2 * pi() * 85;
        $dashOffset = $circumference * (1 - ($score / 100));
    @endphp

    <div class="app-shell">
        <div class="header">
            <h1>Session Completed!</h1>
        </div>

        <div class="content">
            <div class="results-card">
                <div class="stats-list">
                    <div class="stat-item">
                        <span class="stat-label">Duration</span>
                        <span class="stat-value">{{ $durationText ?? '0min' }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Alerts</span>
                        <span class="stat-value">{{ $alerts ?? 0 }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Followed</span>
                        <span class="stat-value">{{ $followed ?? 0 }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Ignored</span>
                        <span class="stat-value">{{ $ignored ?? 0 }}</span>
                    </div>
                </div>

                <div class="hydration-score">
                    <svg class="score-ring" viewBox="0 0 200 200">
                        <!-- Background circle -->
                        <circle cx="100" cy="100" r="85" class="score-ring-bg"></circle>
                        <!-- Progress circle -->
                        <circle
                            cx="100"
                            cy="100"
                            r="85"
                            class="score-ring-progress"
                            style="stroke-dasharray: {{ $circumference }}; stroke-dashoffset: {{ $dashOffset }};"
                        ></circle>
                    </svg>
                    <div class="score-content">
                        <span class="score-label">Hydration<br>Score :</span>
                        <span class="score-percentage">{{ $score }}%</span>
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
