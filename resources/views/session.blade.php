<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
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
            @php
                $showHighSweatRisk = (float) ($temp ?? 0) >= 32 && (float) ($humidity ?? 0) >= 60;
                $initialDurationMinutes = max(1, (int) ($totalDuration ?? 30));
                $initialHours = intdiv($initialDurationMinutes, 60);
                $initialMinutes = $initialDurationMinutes % 60;
                $initialTimerText = sprintf('%02d:%02d:00', $initialHours, $initialMinutes);
            @endphp

            <div class="stats-card">
                <div class="stat-row">
                    <span class="stat-label">Temperature</span>
                    <span class="stat-value" id="temperatureValue">{{ $temp ?? 32 }}°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span class="stat-value" id="humidityValue">{{ $humidity ?? 74 }}%</span>
                </div>
            </div>

            <div class="sweat-risk{{ $showHighSweatRisk ? '' : ' is-hidden' }}" id="sweatRiskWarning" style="{{ $showHighSweatRisk ? '' : 'display:none;' }}">
                <span>HIGH SWEAT RISK</span>
            </div>

            <div class="timer-container">
                <div class="timer" id="sessionTimer">{{ $initialTimerText }}</div>
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
    </div>

    <script>
        // --- Config from backend and routes ---
        const intervalMinutes = {{ $interval ?? 20 }};
        const totalDurationMinutes = {{ $totalDuration ?? 30 }};
        const hydrationAlertUrl = "{{ route('hydration.alert') }}";
        const sessionStateStorageKey = 'hydrationActiveSession';
        const highRiskTempThreshold = 32;
        const highRiskHumidityThreshold = 60;
        const initialTemperature = Number({{ (float) ($temp ?? 32) }});
        const initialHumidity = Number({{ (float) ($humidity ?? 74) }});

        // --- Reminder/timer setup ---
        const intervalSeconds = 10;
        let totalSessionSeconds = Math.max(60, totalDurationMinutes * 60);

        // --- Live session state ---
        let remainingSessionSeconds = totalSessionSeconds;
        let reminderSeconds = intervalSeconds;
        let alerts = 0;
        let followed = 0;
        let ignored = 0;
        let sessionStartedAt = Date.now();
        let pendingReminder = false;
        let lastTickAt = Date.now();

        // --- DOM references ---
        const timerElement = document.getElementById('sessionTimer');
        const sweatRiskWarningElement = document.getElementById('sweatRiskWarning');

        const alertsInput = document.getElementById('alertsInput');
        const followedInput = document.getElementById('followedInput');
        const ignoredInput = document.getElementById('ignoredInput');
        const durationSecondsInput = document.getElementById('durationSecondsInput');
        const sessionForm = document.querySelector('.session-actions');

        const updateHighSweatRiskWarning = (temperature, humidity) => {
            if (!sweatRiskWarningElement) {
                return;
            }

            const showHighRisk = Number.isFinite(temperature)
                && Number.isFinite(humidity)
                && temperature >= highRiskTempThreshold
                && humidity >= highRiskHumidityThreshold;

            sweatRiskWarningElement.classList.toggle('is-hidden', !showHighRisk);
        };

        // --- Query params from hydration alert page ---
        const urlParams = new URLSearchParams(window.location.search);
        const hydrationAction = urlParams.get('hydration_action');
        const shouldResumeFromAlert = Boolean(hydrationAction);

        // --- Save current session state ---
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

        // --- Restore session state after alert navigation ---
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

        // --- Resume or reset session state ---
        if (shouldResumeFromAlert) {
            hydrateState();
        } else {
            sessionStorage.removeItem(sessionStateStorageKey);
        }

        // --- Apply follow/snooze action and clean URL ---
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

        // --- Helper: format seconds to HH:MM:SS ---
        const formatTime = (valueSeconds) => {
            const h = Math.floor(valueSeconds / 3600);
            const m = Math.floor((valueSeconds % 3600) / 60);
            const s = valueSeconds % 60;

            return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        };

        // --- Keep hidden form stats synced ---
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

        // --- Initial UI render ---
        updateHighSweatRiskWarning(initialTemperature, initialHumidity);
        timerElement.textContent = formatTime(remainingSessionSeconds);
        syncStatsToForm();

        // --- Main countdown loop (1 second tick) ---
        const countdown = setInterval(() => {
            if (remainingSessionSeconds <= 0) {
                clearInterval(countdown);
                syncStatsToForm();
                return;
            }

            // Count down session and reminder timers
            remainingSessionSeconds--;
            reminderSeconds--;

            // Trigger hydration reminder when reminder timer ends
            if (reminderSeconds <= 0 && remainingSessionSeconds > 0) {
                alerts++;
                reminderSeconds = intervalSeconds;
                pendingReminder = true;
                syncStatsToForm();
                window.location.href = hydrationAlertUrl;
                return;
            }

            // Update timer display and state each tick
            timerElement.textContent = formatTime(remainingSessionSeconds);
            syncStatsToForm();
        }, 1000);

        // --- Final sync and cleanup when ending session ---
        sessionForm.addEventListener('submit', () => {
            syncStatsToForm();
            sessionStorage.removeItem(sessionStateStorageKey);
        });
    </script>
</body>
</html>