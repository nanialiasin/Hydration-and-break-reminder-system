<!DOCTYPE html>
<html>
<head>
    <title>Hydration Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main role="main" class="app-shell">
    <div class="content">
        <h1 class="page-title">Hydration Settings</h1>

        <section class="card">
            <h2 class="card-title">Training Intensity Levels</h2>
            <div class="settings-grid">
                @foreach($settings as $setting)
                    <div class="setting-item">
                        <div class="setting-intensity">{{ ucfirst($setting->intensity) }}</div>
                        <div class="setting-details">
                            <div class="setting-detail">
                                <span>Hydration Reminder:</span>
                                <span class="setting-value">{{ $setting->hydration_reminder }}m</span>
                            </div>
                            <div class="setting-detail">
                                <span>Break Duration:</span>
                                <span class="setting-value">{{ $setting->break_duration }}m</span>
                            </div>
                            <div class="setting-detail">
                                <span>Break Reminder:</span>
                                <span class="setting-value">{{ $setting->break_reminder }}m</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="edit-link-section">
                <a href="{{ route('hydration.edit') }}" class="btn btn-edit">Edit Settings</a>
            </div>
        </section>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="navi-item active" aria-label="Hydration">
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
</body>
</html>