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

            <section class="todo-list" aria-label="Training tasks">
                <article class="todo-card">
                    <div class="todo-main">
                        <h2>Warm-up (10 min)</h2>
                        <p>Light jog and mobility drills before the main set.</p>
                    </div>
                    <form method="POST" action="{{ route('session.store') }}" class="todo-start-form">
                        @csrf
                        <input type="hidden" name="sport" value="training warm-up">
                        <input type="hidden" name="intensity" value="beginner">
                        <input type="hidden" name="hours" value="0">
                        <input type="hidden" name="minutes" value="10">
                        <button type="submit" class="todo-start-btn">Start</button>
                    </form>
                </article>

                <article class="todo-card">
                    <div class="todo-main">
                        <h2>Main Session (30 min)</h2>
                        <p>Sport-specific training at moderate intensity.</p>
                    </div>
                    <form method="POST" action="{{ route('session.store') }}" class="todo-start-form">
                        @csrf
                        <input type="hidden" name="sport" value="training main">
                        <input type="hidden" name="intensity" value="intermediate">
                        <input type="hidden" name="hours" value="0">
                        <input type="hidden" name="minutes" value="30">
                        <button type="submit" class="todo-start-btn">Start</button>
                    </form>
                </article>

                <article class="todo-card">
                    <div class="todo-main">
                        <h2>Cooldown (5 min)</h2>
                        <p>Breathing and stretching with hydration check.</p>
                    </div>
                    <form method="POST" action="{{ route('session.store') }}" class="todo-start-form">
                        @csrf
                        <input type="hidden" name="sport" value="training cooldown">
                        <input type="hidden" name="intensity" value="beginner">
                        <input type="hidden" name="hours" value="0">
                        <input type="hidden" name="minutes" value="5">
                        <button type="submit" class="todo-start-btn">Start</button>
                    </form>
                </article>
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
            <a href="#" class="nav-item" aria-label="Profile">
                <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
            </a>
        </nav>
    </main>
</body>
</html>
