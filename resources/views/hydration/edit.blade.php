<!DOCTYPE html>
<html>
<head>
    <title>Edit Hydration Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@php
    $selectedPlan = $selectedSetting ?? $settings->first();
    $athleteDisplayName = $athlete?->name ?? 'No athlete selected';
@endphp
<main role="main" class="app-shell">
    <div class="content">
        <header class="card app-header">
            <div class="top-row compact-row">
                <a href="{{ route('hydration.index') }}" class="back-link" aria-label="Back to hydration settings">&#8592;</a>
                <div>
                    <p class="card-kicker">Coach editor</p>
                    <h1 class="page-title page-title-compact">Edit Hydration Settings</h1>
                </div>
            </div>

            <div class="header-tools">
                @if($athletes && $athletes->count() > 1)
                    <form method="GET" action="{{ route('hydration.edit') }}" class="athlete-switcher">
                        <label class="field-label" for="athlete_switcher_edit">Athlete</label>
                        <select id="athlete_switcher_edit" name="athlete_id" class="field-input" onchange="this.form.submit()">
                            @foreach($athletes as $item)
                                <option value="{{ $item->athlete_id }}" {{ $selectedAthleteId === $item->athlete_id ? 'selected' : '' }}>
                                    {{ $item->name }} • {{ $item->sport ?? 'No sport' }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                @endif

                <div class="header-summary">
                    <span class="summary-pill">{{ $athlete?->athlete_id ?? 'No athlete' }}</span>
                    <span class="summary-pill soft">{{ $selectedPlan?->intensity ? ucfirst($selectedPlan->intensity) : 'Beginner' }} plan</span>
                </div>
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

        <section class="card form-card">
            <div class="card-heading">
                <div>
                    <p class="card-kicker">Coach controls</p>
                    <h2 class="card-title">Base values</h2>
                </div>
                <span class="card-badge">Saved per intensity</span>
            </div>

            <p class="plan-note">Base reminder before weight and weather adjustments.</p>

            <form action="{{ route('hydration.update') }}" method="POST" class="settings-form">
                @csrf

                @foreach($settings as $setting)
                    <article class="setting-form-block">
                        <div class="setting-header">
                            <div>
                                <h3 class="setting-intensity">{{ ucfirst($setting->intensity ?? $setting->level) }}</h3>
                            </div>
                            <span class="setting-pill">Plan #{{ $setting->id }}</span>
                        </div>

                        <div class="field-grid">
                            <div class="field-row">
                                <label class="field-label" for="hydration_reminder_{{ $setting->id }}">Hydration reminder every (min)</label>
                                <input id="hydration_reminder_{{ $setting->id }}" type="number" min="1" name="settings[{{ $setting->id }}][hydration_reminder]" value="{{ $setting->hydration_reminder }}" class="field-input" required>
                            </div>

                            <div class="field-row">
                                <label class="field-label" for="break_duration_{{ $setting->id }}">Break duration (min)</label>
                                <input id="break_duration_{{ $setting->id }}" type="number" min="1" name="settings[{{ $setting->id }}][break_duration]" value="{{ $setting->break_duration }}" class="field-input" required>
                            </div>

                            <div class="field-row">
                                <label class="field-label" for="break_reminder_{{ $setting->id }}">Break reminder every (min)</label>
                                <input id="break_reminder_{{ $setting->id }}" type="number" min="1" name="settings[{{ $setting->id }}][break_reminder]" value="{{ $setting->break_reminder }}" class="field-input" required>
                            </div>
                        </div>
                    </article>

                    @if(!$loop->last)
                        <hr class="setting-divider">
                    @endif
                @endforeach

                <div class="form-actions form-actions-triple">
                    <button type="button" id="open_preview_btn" class="btn btn-preview">Preview</button>
                    <a href="{{ route('hydration.index', ['athlete_id' => $selectedAthleteId]) }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-save">Save</button>
                </div>
            </form>
        </section>
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
    const previewButton = document.getElementById('open_preview_btn');
    if (!previewButton) {
        return;
    }

    previewButton.addEventListener('click', function () {
        const params = new URLSearchParams();
        const selectedAthleteId = '{{ $selectedAthleteId }}';

        if (selectedAthleteId) {
            params.set('athlete_id', selectedAthleteId);
        }

        params.set('preview_intensity', '{{ $selectedPlan?->id }}');
        params.set('preview_weight', '{{ (float) ($athlete?->weight ?? 70) }}');
        params.set('preview_temp', '32');
        params.set('preview_humidity', '75');
        params.set('preview_duration', '90');

        @foreach($settings as $setting)
            const hydrationReminder{{ $setting->id }} = document.getElementById('hydration_reminder_{{ $setting->id }}');
            const breakDuration{{ $setting->id }} = document.getElementById('break_duration_{{ $setting->id }}');
            const breakReminder{{ $setting->id }} = document.getElementById('break_reminder_{{ $setting->id }}');

            if (hydrationReminder{{ $setting->id }}) {
                params.set('settings[{{ $setting->id }}][hydration_reminder]', hydrationReminder{{ $setting->id }}.value || '{{ $setting->hydration_reminder }}');
            }
            if (breakDuration{{ $setting->id }}) {
                params.set('settings[{{ $setting->id }}][break_duration]', breakDuration{{ $setting->id }}.value || '{{ $setting->break_duration }}');
            }
            if (breakReminder{{ $setting->id }}) {
                params.set('settings[{{ $setting->id }}][break_reminder]', breakReminder{{ $setting->id }}.value || '{{ $setting->break_reminder }}');
            }
        @endforeach

        window.location.href = '{{ route('hydration.preview') }}?' + params.toString();
    });
});
</script>
</body>
</html>