<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    @vite('resources/css/home.css')
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <h1 class="greeting">Welcome, User.</h1>

            <section class="stats-card">
                <div class="stat-row">
                    <span class="stat-label">Temperature</span>
                    <span class="stat-value">{{ $temp ?? 32 }}°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span class="stat-value">{{ $humidity ?? 74 }}%</span>
                </div>
            </section>

            <section class="timer-card">
                <h2 class="timer-title">Keep your streak!</h2>
                
                <div class="timer-ring">
                    <svg viewBox="0 0 200 200" class="progress-ring">
                        <circle cx="100" cy="100" r="85" class="ring-bg"></circle>
                        <circle id="ringProgress" cx="100" cy="100" r="85" class="ring-progress" style="stroke-dasharray: 534; stroke-dashoffset: 0;"></circle>
                    </svg>
                    <div class="timer-text">
                        <p class="timer-label">Next drink in :</p>
                        <p id="homeCountdown" class="timer-countdown">00:00</p>
                    </div>
                </div>

                <button class="drink-btn">Drink Now</button>
            </section>

            <section class="daily-stats-card" aria-label="Daily stats">
                <h2 class="daily-stats-title">Daily Stats</h2>
                <div class="daily-stats-grid">
                    <article class="daily-stat-box">
                        <p class="daily-stat-value">{{ $dayStreak ?? 0 }}</p>
                        <p class="daily-stat-label">Day Streak</p>
                    </article>
                    <article class="daily-stat-box">
                        <p class="daily-stat-value">{{ $weeklyAvg ?? 1500 }}ml</p>
                        <p class="daily-stat-label">Weekly Avg</p>
                    </article>
                </div>
            </section>
        </div>

        <nav class="bottom-nav" aria-label="Main navigation">
            <a href="#" class="nav-item active" aria-label="Home">
                <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
            </a>
            <a href="{{ route('training') }}" class="nav-item" aria-label="Training">
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

    <script>
        const totalMinutes = {{ $interval ?? 12 }};
        const intervalSeconds = totalMinutes * 60;
        let totalSeconds = intervalSeconds;
        let didExpire = false;

        const countdownElement = document.getElementById('homeCountdown');
        const ringProgress = document.getElementById('ringProgress');
        const drinkButton = document.querySelector('.drink-btn');
        const circumference = 534; 

        function renderTimer() {
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            
            countdownElement.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

            const offset = circumference - (totalSeconds / intervalSeconds) * circumference;
            ringProgress.style.strokeDashoffset = offset;
        }

        function resetTimer() {
            totalSeconds = intervalSeconds;
            didExpire = false;
            renderTimer();
        }

        function updateTimer() {
            if (totalSeconds > 0) {
                totalSeconds--;
                renderTimer();
            }

            if (totalSeconds <= 0 && !didExpire) {
                didExpire = true;
                alert("Reminder: Time to hydrate!");
            }
        }

        drinkButton.addEventListener('click', resetTimer);

        const timerInterval = setInterval(updateTimer, 1000);
        renderTimer();
    </script>
</body>
</html>