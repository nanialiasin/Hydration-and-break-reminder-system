<!DOCTYPE html>
<html>
<head>
    <title>Hydration Preview</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@php
    $selectedPlan = $selectedSetting ?? $settings->first();
    $athleteDisplayName = $athlete?->name ?? 'No athlete selected';
    $previewAthlete = [
        'name' => $athlete?->name,
        'weight' => $athlete?->weight,
        'height' => $athlete?->height,
        'bmi' => $athlete?->bmi,
        'intensity' => $athlete?->intensity,
    ];
    $previewSettings = $settings->map(function ($setting) {
        return [
            'id' => $setting->id,
            'label' => ucfirst($setting->intensity ?? $setting->level),
            'hydration_reminder' => (int) $setting->hydration_reminder,
            'break_duration' => (int) $setting->break_duration,
            'break_reminder' => (int) $setting->break_reminder,
        ];
    })->values();
@endphp
<main role="main" class="app-shell">
    <div class="content">
        <header class="card app-header">
            <div class="top-row compact-row">
                <a href="{{ route('hydration.edit', ['athlete_id' => $selectedAthleteId]) }}" class="back-link" aria-label="Back to hydration editor">&#8592;</a>
                <div>
                    <p class="card-kicker">Coach preview</p>
                    <h1 class="page-title page-title-compact">Live Hydration Preview</h1>
                </div>
            </div>

            <div class="header-summary">
                <span class="summary-pill">{{ $athlete?->athlete_id ?? 'No athlete' }}</span>
                <span class="summary-pill soft">{{ $selectedPlan?->intensity ? ucfirst($selectedPlan->intensity) : 'Beginner' }} plan</span>
            </div>
        </header>

        <section class="card athlete-card">
            <div class="athlete-top">
                <div class="athlete-headline">
                    <p class="card-kicker">Live athlete profile</p>
                    <div class="athlete-name-row">
                        <h2 class="card-title">{{ $athleteDisplayName }}</h2>
                        <span class="setting-pill athlete-id-pill">{{ $athlete?->athlete_id ?? 'No athlete' }}</span>
                    </div>
                </div>
            </div>

            <div class="tag-row">
                <span class="tag">{{ $athlete?->weight ? $athlete->weight . ' kg' : 'N/A' }}</span>
                <span class="tag">{{ $athlete?->height ? $athlete->height . ' cm' : 'N/A' }}</span>
                <span class="tag">{{ $athlete?->bmi ?? 'N/A' }}</span>
                <span class="tag">{{ $athlete?->intensity ? ucfirst($athlete->intensity) : 'Intensity not set' }}</span>
            </div>
        </section>

        <section class="card preview-card">
            <div class="card-heading">
                <div>
                    <p class="card-kicker">Live preview</p>
                    <h2 class="card-title">See how the new logic changes reminders</h2>
                </div>
                <span class="card-badge">Interactive</span>
            </div>

            <div class="preview-controls">
                <div class="field-row">
                    <label class="field-label" for="preview_intensity">Intensity</label>
                    <select id="preview_intensity" class="field-input">
                        @foreach($settings as $setting)
                            <option value="{{ $setting->id }}" {{ $selectedPlan && $selectedPlan->id === $setting->id ? 'selected' : '' }}>{{ ucfirst($setting->intensity ?? $setting->level) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-row">
                    <label class="field-label" for="preview_weight">Athlete weight (kg)</label>
                    <input id="preview_weight" type="number" min="1" step="1" value="{{ (float) ($athlete?->weight ?? 70) }}" class="field-input">
                </div>
                <div class="field-row">
                    <label class="field-label" for="preview_temp">Temperature (°C)</label>
                    <input id="preview_temp" type="number" min="0" step="1" value="32" class="field-input">
                </div>
                <div class="field-row">
                    <label class="field-label" for="preview_humidity">Humidity (%)</label>
                    <input id="preview_humidity" type="number" min="0" max="100" step="1" value="75" class="field-input">
                </div>
                <div class="field-row">
                    <label class="field-label" for="preview_duration">Session duration (min)</label>
                    <input id="preview_duration" type="number" min="1" step="1" value="90" class="field-input">
                </div>
            </div>

            <div class="preview-output">
                <div class="preview-summary">
                    <div class="preview-number">
                        <span class="preview-label">Daily target</span>
                        <strong id="preview_daily_target">0ml</strong>
                    </div>
                    <div class="preview-number">
                        <span class="preview-label">Drink per reminder</span>
                        <strong id="preview_drink_size">0ml</strong>
                    </div>
                    <div class="preview-number">
                        <span class="preview-label">Reminder interval</span>
                        <strong id="preview_interval">0 min</strong>
                    </div>
                </div>

                <div class="preview-notes" id="preview_notes"></div>

                <p class="preview-message" id="preview_message"></p>
            </div>
        </section>

        <div class="form-actions preview-actions">
            <a href="{{ route('hydration.edit', ['athlete_id' => $selectedAthleteId]) }}" class="btn btn-cancel">Back to editor</a>
        </div>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index', ['athlete_id' => $selectedAthleteId]) }}" class="navi-item active" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="navi-item" aria-label="Activity">
            <img src="{{ asset('images/Create.svg') }}" alt="Activity" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="navi-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const settings = @json($previewSettings);
    const athlete = @json($previewAthlete);
    const queryParams = new URLSearchParams(window.location.search);

    const intensitySelect = document.getElementById('preview_intensity');
    const weightInput = document.getElementById('preview_weight');
    const tempInput = document.getElementById('preview_temp');
    const humidityInput = document.getElementById('preview_humidity');
    const durationInput = document.getElementById('preview_duration');

    const dailyTargetNode = document.getElementById('preview_daily_target');
    const drinkSizeNode = document.getElementById('preview_drink_size');
    const intervalNode = document.getElementById('preview_interval');
    const notesNode = document.getElementById('preview_notes');
    const messageNode = document.getElementById('preview_message');

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

    const toInt = (value, fallback) => {
        const parsed = parseInt(value, 10);
        return Number.isNaN(parsed) ? fallback : parsed;
    };

    const calculateEnvironmentInterval = (temp, humidity, duration) => {
        let interval = 30;

        if (temp >= 35) {
            interval -= 10;
        } else if (temp >= 30) {
            interval -= 7;
        } else if (temp >= 27) {
            interval -= 3;
        }

        if (humidity >= 80) {
            interval -= 5;
        } else if (humidity >= 60) {
            interval -= 2;
        }

        if (duration >= 90) {
            interval -= 5;
        }

        return clamp(interval, 10, 30);
    };

    const calculateDailyTarget = (weightKg, temp, humidity, duration) => {
        const baseTarget = clamp(Math.round(weightKg * 35), 1500, 3000);
        let multiplier = 1;

        if (temp >= 32) {
            multiplier += 0.10;
        } else if (temp >= 30) {
            multiplier += 0.05;
        }

        if (humidity >= 80) {
            multiplier += 0.10;
        } else if (humidity >= 70) {
            multiplier += 0.05;
        }

        if (duration >= 90) {
            multiplier += 0.10;
        } else if (duration >= 60) {
            multiplier += 0.05;
        }

        return clamp(Math.round(baseTarget * multiplier), 1500, 3000);
    };

    const calculateDrinkSize = (dailyTarget) => clamp(Math.round(dailyTarget / 18), 150, 250);

    const calculateInterval = (baseReminder, temp, humidity, duration, dailyTarget, drinkSize, weightKg) => {
        const environmentInterval = calculateEnvironmentInterval(temp, humidity, duration);
        const remindersNeeded = Math.max(1, Math.ceil(dailyTarget / drinkSize));
        const trackingWindow = Math.max(duration, 720);
        const targetInterval = Math.max(10, Math.floor(trackingWindow / remindersNeeded));

        if (weightKg > 0) {
            return Math.min(environmentInterval, targetInterval);
        }

        return Math.max(baseReminder + (environmentInterval - 30), 5);
    };

    settings.forEach((setting) => {
        const hydrationReminder = queryParams.get(`settings[${setting.id}][hydration_reminder]`);
        const breakDuration = queryParams.get(`settings[${setting.id}][break_duration]`);
        const breakReminder = queryParams.get(`settings[${setting.id}][break_reminder]`);

        setting.hydration_reminder = toInt(hydrationReminder, setting.hydration_reminder);
        setting.break_duration = toInt(breakDuration, setting.break_duration);
        setting.break_reminder = toInt(breakReminder, setting.break_reminder);
    });

    const initialIntensity = queryParams.get('preview_intensity');
    const initialWeight = queryParams.get('preview_weight');
    const initialTemp = queryParams.get('preview_temp');
    const initialHumidity = queryParams.get('preview_humidity');
    const initialDuration = queryParams.get('preview_duration');

    if (initialIntensity) {
        intensitySelect.value = initialIntensity;
    }
    if (initialWeight) {
        weightInput.value = initialWeight;
    }
    if (initialTemp) {
        tempInput.value = initialTemp;
    }
    if (initialHumidity) {
        humidityInput.value = initialHumidity;
    }
    if (initialDuration) {
        durationInput.value = initialDuration;
    }

    const updatePreview = () => {
        const selectedSetting = settings.find((setting) => String(setting.id) === String(intensitySelect.value)) || settings[0];
        const weightKg = Math.max(1, toInt(weightInput.value || '70', 70));
        const temp = toInt(tempInput.value || '32', 32);
        const humidity = toInt(humidityInput.value || '75', 75);
        const duration = Math.max(1, toInt(durationInput.value || '90', 90));

        const dailyTarget = calculateDailyTarget(weightKg, temp, humidity, duration);
        const drinkSize = calculateDrinkSize(dailyTarget);
        const interval = calculateInterval(selectedSetting.hydration_reminder, temp, humidity, duration, dailyTarget, drinkSize, weightKg);

        dailyTargetNode.textContent = `${dailyTarget}ml`;
        drinkSizeNode.textContent = `${drinkSize}ml`;
        intervalNode.textContent = `${interval} min`;

        const notes = [];
        notes.push(athlete && athlete.weight ? `${athlete.name || 'Athlete'} profile` : 'Generic preview');
        notes.push(weightKg >= 80 ? '+ heavier athlete' : weightKg <= 55 ? '+ lighter athlete' : 'athlete weight');
        notes.push(temp >= 35 ? '+ hot weather' : temp >= 30 ? '+ warm weather' : temp >= 27 ? '+ mild heat' : 'normal temperature');
        notes.push(humidity >= 80 ? '+ high humidity' : humidity >= 60 ? '+ moderate humidity' : 'low humidity');
        notes.push(duration >= 90 ? '+ long session' : duration >= 60 ? '+ medium session' : 'short session');

        notesNode.innerHTML = notes.map((note) => `<span class="note-chip">${note}</span>`).join('');
        messageNode.textContent = `Drink about ${drinkSize}ml every ${interval} minutes to reach ${dailyTarget}ml today.`;
    };

    [intensitySelect, weightInput, tempInput, humidityInput, durationInput].forEach((element) => {
        element.addEventListener('input', updatePreview);
        element.addEventListener('change', updatePreview);
    });

    updatePreview();
});
</script>
</body>
</html>
