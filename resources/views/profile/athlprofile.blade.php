<!DOCTYPE html>
<html>
<head>
    <title>Athlete Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/athlprofile.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="profile-title">Profile</h1>

        <section class="card profile-card">
            <div class="athlete-avatar">
                <img src="{{ $athlete?->profile_pic && $athlete?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $athlete->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicView">
            </div>
            <div class="athlete-id-wrap">
                <h2 class="athlete-id">Athlete ID: {{ $athlete?->athlete_id ?? 'N/A' }}</h2>
            </div>

            <div class="profile-info">
                <div class="info-row"><span>Name</span><strong>{{ $athlete?->name ?? ($name ?? '') }}</strong></div>
                <div class="info-row"><span>Email</span><strong>{{ $athlete?->email ?? ($email ?? '') }}</strong></div>
                <div class="info-row"><span>Weight (kg)</span><strong>{{ $athlete?->weight ?? (isset($weight) ? $weight : '') }}</strong></div>
                <div class="info-row"><span>Height (cm)</span><strong>{{ $athlete?->height ?? (isset($height) ? $height : '') }}</strong></div>
                <div class="info-row"><span>BMI</span><strong>{{ $athlete?->bmi ?? ((isset($weight) && isset($height)) ? round($weight / pow($height / 100, 2), 2) : '') }}</strong></div>
                <div class="info-row"><span>Sport</span><strong>{{ $athlete?->sport ?? ($sport ?? '') }}</strong></div>
                <div class="info-row"><span>Training Intensity</span><strong>{{ $athlete?->intensity ?? ($training_intensity ?? '') }}</strong></div>
                <div class="info-row"><span>Status</span><strong class="status-active">Active</strong></div>
            </div>

            <div class="edit-profile-row">
                <a href="{{ route('profile.editprofile', $athlete?->athlete_id) }}" class="edit-profile-btn">Edit Profile</a>
            </div>
        </section>

        <section class="card toggle-card">
            <span class="toggle-label">Stay Logged In</span>
            <form method="POST" action="{{ route('athlete.stayloggedin') }}" id="stayLoggedInForm">
                @csrf
                <label class="switch">
                    <input type="checkbox" id="stayLoggedInSwitch" name="stay_logged_in" value="1" {{ ($athlete?->stay_logged_in ?? false) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </form>
        </section>

        <section class="card slider-card">
            <label>Volume for Alerts</label>
            <input type="range" min="0" max="100" value="{{ $athlete?->alert_volume ?? 50 }}">
            <label class="slider-label-spacing">Volume for Reminders</label>
            <input type="range" min="0" max="100" value="{{ $athlete?->reminder_volume ?? 50 }}">
        </section>

        <div class="button-group">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger">Log Out</button>
            </form>
            <form method="GET" action="{{ route('profile.delete.confirm') }}">
                <button class="btn btn-danger">Delete Account</button>
            </form>
        </div>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('training') }}" class="navi-item" aria-label="Training">
            <img src="{{ asset('images/Training Button.svg') }}" alt="Training" width="24" height="24">
        </a>
        <a href="{{ route('history') }}" class="navi-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('profile.athlprofile', $athlete?->athlete_id) }}" class="navi-item active" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stayLoggedInSwitch = document.getElementById('stayLoggedInSwitch');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    if (stayLoggedInSwitch) {
        stayLoggedInSwitch.addEventListener('change', function() {
            fetch("{{ route('athlete.stayloggedin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    stay_logged_in: stayLoggedInSwitch.checked ? 1 : 0
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Optionally show a toast or update UI
                }
            });
        });
    }
});
</script>
</body>
</html>