<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Session</title>
    @vite('resources/css/session.css')
</head>
<body>
    <div class="app-shell">
        <div class="header">
            <a href="{{ route('home') }}" class="back-button" aria-label="Go back">
                <span>←</span>
            </a>
            <h1>Session</h1>
        </div>

        <div class="content">
            <div class="stats-card">
                <div class="stat-row">
                    <span class="stat-label">Temperature</span>
                    <span class="stat-value">{{ $temp ?? 32 }}°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span class="stat-value">{{ $humidity ?? 74 }}%</span>
                </div>
            </div>

            <div class="sweat-risk">
                <span>HIGH SWEAT RISK</span>
            </div>

            <div class="timer-container">
                <div class="timer" id="sessionTimer">00:00:00</div>
            </div>

            <form method="POST" action="{{ route('session.end') }}" class="session-actions">
                @csrf
                <input type="hidden" name="alerts" id="alertsInput" value="0">
                <input type="hidden" name="followed" id="followedInput" value="0">
                <input type="hidden" name="ignored" id="ignoredInput" value="0">
                <input type="hidden" name="duration_seconds" id="durationSecondsInput" value="0">
                <button type="submit" class="btn btn-end">End Session</button>
            </form>
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

    <script>
        const intervalMinutes = {{ $interval ?? 20 }};
        const totalDurationMinutes = {{ $totalDuration ?? 30 }};
        const hydrationAlertUrl = "{{ route('hydration.alert') }}";
        const sessionStateStorageKey = 'hydrationActiveSession';

        const intervalSeconds = 10;
        let totalSessionSeconds = Math.max(60, totalDurationMinutes * 60);

        let remainingSessionSeconds = totalSessionSeconds;
        let reminderSeconds = intervalSeconds;
        let alerts = 0;
        let followed = 0;
        let ignored = 0;
        let sessionStartedAt = Date.now();
        let pendingReminder = false;
        let lastTickAt = Date.now();

        const timerElement = document.getElementById('sessionTimer');

        const alertsInput = document.getElementById('alertsInput');
        const followedInput = document.getElementById('followedInput');
        const ignoredInput = document.getElementById('ignoredInput');
        const durationSecondsInput = document.getElementById('durationSecondsInput');
        const sessionForm = document.querySelector('.session-actions');

        const urlParams = new URLSearchParams(window.location.search);
        const hydrationAction = urlParams.get('hydration_action');
        const shouldResumeFromAlert = Boolean(hydrationAction);

        const persistState = () => {
            const state = {
                totalSessionSeconds,
                remainingSessionSeconds,
                reminderSeconds,
                alerts,
                followed,
                ignored,
                sessionStartedAt,
                pendingReminder,
                lastTickAt: Date.now(),
            };

            sessionStorage.setItem(sessionStateStorageKey, JSON.stringify(state));
        };

        const hydrateState = () => {
            const raw = sessionStorage.getItem(sessionStateStorageKey);

            if (!raw) {
                return;
            }

            try {
                const state = JSON.parse(raw);

                totalSessionSeconds = Math.max(60, Number(state.totalSessionSeconds ?? totalSessionSeconds));
                remainingSessionSeconds = Math.max(0, Number(state.remainingSessionSeconds ?? remainingSessionSeconds));
                reminderSeconds = Math.max(1, Number(state.reminderSeconds ?? reminderSeconds));
                alerts = Math.max(0, Number(state.alerts ?? alerts));
                followed = Math.max(0, Number(state.followed ?? followed));
                ignored = Math.max(0, Number(state.ignored ?? ignored));
                sessionStartedAt = Number(state.sessionStartedAt ?? sessionStartedAt);
                pendingReminder = Boolean(state.pendingReminder ?? false);
                lastTickAt = Number(state.lastTickAt ?? lastTickAt);

                const elapsedWhileAway = Math.floor((Date.now() - lastTickAt) / 1000);

                if (elapsedWhileAway > 0) {
                    remainingSessionSeconds = Math.max(0, remainingSessionSeconds - elapsedWhileAway);

                    if (!pendingReminder) {
                        reminderSeconds = Math.max(1, reminderSeconds - elapsedWhileAway);
                    }
                }
            } catch (error) {
                sessionStorage.removeItem(sessionStateStorageKey);
            }
        };

        if (shouldResumeFromAlert) {
            hydrateState();
        } else {
            sessionStorage.removeItem(sessionStateStorageKey);
        }

        if (pendingReminder && hydrationAction) {
            if (hydrationAction === 'followed') {
                followed++;
            } else if (hydrationAction === 'snoozed') {
                ignored++;
            }

            pendingReminder = false;
            persistState();

            urlParams.delete('hydration_action');
            const cleanedUrl = `${window.location.pathname}${urlParams.toString() ? `?${urlParams.toString()}` : ''}`;
            window.history.replaceState({}, '', cleanedUrl);
        }

        const formatTime = (valueSeconds) => {
            const h = Math.floor(valueSeconds / 3600);
            const m = Math.floor((valueSeconds % 3600) / 60);
            const s = valueSeconds % 60;

            return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        };

        const syncStatsToForm = () => {
            const elapsedByClock = Math.floor((Date.now() - sessionStartedAt) / 1000);
            const elapsedByTimer = totalSessionSeconds - remainingSessionSeconds;
            const elapsed = Math.max(elapsedByClock, elapsedByTimer);

            alertsInput.value = alerts;
            followedInput.value = followed;
            ignoredInput.value = ignored;
            durationSecondsInput.value = Math.max(0, elapsed);

            persistState();
        };

        timerElement.textContent = formatTime(remainingSessionSeconds);
        syncStatsToForm();

        const countdown = setInterval(() => {
            if (remainingSessionSeconds <= 0) {
                clearInterval(countdown);
                syncStatsToForm();
                return;
            }

            remainingSessionSeconds--;
            reminderSeconds--;

            if (reminderSeconds <= 0 && remainingSessionSeconds > 0) {
                alerts++;
                reminderSeconds = intervalSeconds;
                pendingReminder = true;
                syncStatsToForm();
                window.location.href = hydrationAlertUrl;
                return;
            }

            timerElement.textContent = formatTime(remainingSessionSeconds);
            syncStatsToForm();
        }, 1000);

        sessionForm.addEventListener('submit', () => {
            syncStatsToForm();
            sessionStorage.removeItem(sessionStateStorageKey);
        });
    </script>
</body>
</html>