<!DOCTYPE html>
<html>
<head>
    <title>Athlete Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/athlprofile.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="background-color: #e9ecf5;">
@if(!$athlete)
    <main class="app-shell" role="main">
        <div class="container" style="max-width:430px;margin:0 auto;padding:0;margin-bottom:80px;">
            <div class="profile-title" style="font-size:28px;font-weight:bold;margin-bottom:20px;text-align:left;margin-left:0;">Profile</div>
            <div class="card" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);padding:24px 28px;margin-bottom:24px;background:#fff;position:relative;text-align:center;">
                <h4>You have not created a profile yet.</h4>
                <a href="{{ route('athletes.create') }}" class="btn btn-dark" style="margin-top:18px;">Create Profile</a>
            </div>
        </div>
    </main>
@else
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
            <hr style="border:none; border-top:1.5px solid #e9ecf5; margin-bottom:18px; margin-top:0;">
            <div style="font-size:16px;line-height:1.7;color:#222;">
                <p><strong>Name:</strong> {{ $athlete?->name ?? ($name ?? '') }}</p>
                <p><strong>Email:</strong> {{ $athlete?->email ?? ($email ?? '') }}</p>
                <p><strong>Weight (kg):</strong> {{ $athlete?->weight ?? (isset($weight) ? $weight : '') }}</p>
                <p><strong>Height (cm):</strong> {{ $athlete?->height ?? (isset($height) ? $height : '') }}</p>
                <p><strong>BMI:</strong> {{ $athlete?->bmi ?? ((isset($weight) && isset($height)) ? round($weight / pow($height / 100, 2), 2) : '') }}</p>
                <p><strong>Sport:</strong> {{ $athlete?->sport ?? ($sport ?? '') }}</p>
                <p><strong>Training Intensity:</strong> {{ $athlete?->training_intensity ?? $athlete?->intensity ?? ($training_intensity ?? '') }}</p>
                <p><strong>Status:</strong> 
                    @if(($athlete?->status ?? $status ?? 'active') === 'inactive')
                        <span class="status-inactive">Inactive</span>
                    @else
                        <span class="status-active">Active</span>
                    @endif
                </p>
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
@endif
<nav class="nav-bar" aria-label="Main navigation" style="position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);z-index:1000;display:flex;justify-content:space-between;align-items:center;background:#000;border-radius:16px 16px 0 0;box-shadow:0 -2px 12px rgba(0,0,0,0.08);padding:10px 32px 8px 32px;max-width:100vw;border-top:1.5px solid #e0e7ef;margin:0;">
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