<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training</title>
    @vite('resources/css/training.css')
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <header class="page-header">
                <a href="{{ route('home') }}" class="back-btn" aria-label="Back to home">←</a>
                <h1 class="page-title">To-Do Session</h1>
            </header>

        <div class="center-btn" style="margin-bottom: 1.5rem;">
            <a href="{{ route('calculate') }}" class="calculate-sips-btn">
                How To Calculate Average Sip
            </a>
        </div>

            <section class="todo-list" aria-label="Training tasks">
                @forelse(($todoSessions ?? collect()) as $todo)
                    @php
                        $plannedMinutes = max(0, (int) ($todo->planned_duration_minutes ?? 0));
                        $hours = intdiv($plannedMinutes, 60);
                        $minutes = $plannedMinutes % 60;
                        $intensityLabel = ucfirst($todo->intensity ?? 'beginner');
                    @endphp

                    <article class="todo-card">
                        <div class="todo-main">
                            <h2>{{ ucfirst($todo->sport ?? 'General Training') }} ({{ $plannedMinutes }} min)</h2>
                            <p>{{ $intensityLabel }} intensity session assigned by coach.</p>
                        </div>
                        <form method="POST" action="{{ route('session.store') }}" class="todo-start-form">
                            @csrf
                            <input type="hidden" name="assigned_session_id" value="{{ $todo->id }}">
                            <input type="hidden" name="sport" value="{{ $todo->sport }}">
                            <input type="hidden" name="intensity" value="{{ $todo->intensity }}">
                            <input type="hidden" name="hours" value="{{ $hours }}">
                            <input type="hidden" name="minutes" value="{{ $minutes }}">
                            <button type="submit" class="todo-start-btn">Start</button>
                        </form>
                    </article>
                @empty
                    <article class="todo-card">
                        <div class="todo-main">
                            <h2>No session assigned</h2>
                            <p>Your coach has not assigned a session yet.</p>
                        </div>
                    </article>
                @endforelse
            </section>
        </div>

        <nav class="bottom-nav" aria-label="Main navigation">
            <a href="{{ route('home') }}" class="nav-item" aria-label="Home">
                <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
            </a>
            <a href="{{ route('training') }}" class="nav-item active" aria-label="Training">
                <img src="{{ asset('images/Training Button.svg') }}" alt="Training" width="24" height="24">
            </a>
            <a href="{{ route('history') }}" class="nav-item" aria-label="History">
                <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
            </a>
            @if(isset($athlete) && $athlete?->athlete_id)
                <a href="{{ route('profile.athlprofile', $athlete->athlete_id) }}" class="nav-item" aria-label="Profile">
                    <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
                </a>
            @else
                <a href="#" class="nav-item disabled" aria-label="Profile" style="pointer-events:none;opacity:0.5;">
                    <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
                </a>
            @endif
        </nav>
    </main>
</body>
</html>
