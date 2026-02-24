<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydration Streak</title>
    @vite('resources/css/streak.css')
</head>
<body>
    <main class="shell" role="main">
        <section class="streak-card" aria-label="Hydration streak">
            <p class="streak-intro">You're on a</p>
            <div class="streak-row">
                <span class="streak-count">{{ $dayStreak ?? 0 }}</span>
                <img src="{{ asset('images/Main Flame.svg') }}" alt="Flame" class="streak-flame" width="64" height="64">
            </div>
            <p class="streak-title">Hydration<br>Streak!</p>
            <a class="btn-continue" href="{{ route('home') }}">Continue</a>
        </section>
    </main>
</body>
</html>
