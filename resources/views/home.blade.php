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
                    <span id="temperatureValue" class="stat-value">{{ $temp ?? 32 }}°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span id="humidityValue" class="stat-value">{{ $humidity ?? 74 }}%</span>
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

    <script>
        // --- Timer configuration ---
        const totalMinutes = {{ $interval ?? 12 }};
        const intervalSeconds = totalMinutes * 60;
        let totalSeconds = intervalSeconds;
        let didExpire = false;

        // --- DOM references for timer UI ---
        const countdownElement = document.getElementById('homeCountdown');
        const ringProgress = document.getElementById('ringProgress');
        const drinkButton = document.querySelector('.drink-btn');
        const circumference = 534; 
        const temperatureValueElement = document.getElementById('temperatureValue');
        const humidityValueElement = document.getElementById('humidityValue');
        const sensorLatestUrl = "{{ route('sensor.latest') }}";
        let lastSensorState = null;

        // --- Log sensor state changes in DevTools console only ---
        function setSensorState(isOnline, updatedAtText) {
            if (lastSensorState === isOnline) {
                return;
            }

            lastSensorState = isOnline;

            if (isOnline) {
                console.info(`[Sensor] Online. ${updatedAtText}`);
                return;
            }

            console.warn(`[Sensor] Offline. ${updatedAtText}`);
        }

        // --- Poll latest sensor values and refresh cards ---
        async function refreshSensorValues() {
            try {
                const response = await fetch(sensorLatestUrl, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' },
                    cache: 'no-store',
                });

                if (!response.ok) {
                    throw new Error(`Sensor endpoint returned ${response.status}`);
                }

                const payload = await response.json();
                const temperature = Number(payload.temperature);
                const humidity = Number(payload.humidity);
                const source = String(payload.source ?? 'fallback');

                if (Number.isFinite(temperature)) {
                    temperatureValueElement.textContent = `${temperature.toFixed(1)}°C`;
                }

                if (Number.isFinite(humidity)) {
                    humidityValueElement.textContent = `${humidity.toFixed(1)}%`;
                }

                const isOnline = source === 'sensor';
                const updatedAt = payload.updated_at
                    ? `Updated ${payload.updated_at}`
                    : (isOnline ? 'Live sensor feed' : 'Using fallback values');

                setSensorState(isOnline, updatedAt);
            } catch (error) {
                setSensorState(false, 'Unable to reach sensor endpoint');
            }
        }

        // --- Render countdown text and ring progress ---
        function renderTimer() {
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            
            countdownElement.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

            const offset = circumference - (totalSeconds / intervalSeconds) * circumference;
            ringProgress.style.strokeDashoffset = offset;
        }

        // --- Reset timer when user drinks now ---
        function resetTimer() {
            totalSeconds = intervalSeconds;
            didExpire = false;
            renderTimer();
        }

        // --- Tick timer and show hydration reminder at zero ---
        function updateTimer() {
            if (totalSeconds > 0) {
                totalSeconds--;
                renderTimer();
            }

            // Reminder pop-up
            if (totalSeconds <= 0 && !didExpire) {
                didExpire = true;
                alert("Reminder: Time to hydrate!");
            }
        }

        // --- Bind actions and start timer loop ---
        drinkButton.addEventListener('click', resetTimer);

        const timerInterval = setInterval(updateTimer, 1000);
        renderTimer();

        // --- Start live sensor polling ---
        refreshSensorValues();
        const sensorPollingInterval = setInterval(refreshSensorValues, 1000);
    </script>
</body>
</html>