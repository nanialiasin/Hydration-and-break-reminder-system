<!DOCTYPE html>
<html>
<head>
    <title>Athlete Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/athlprofile.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
        .switch input:checked + .slider {
            background:rgb(153, 255, 139);
        }
        .switch .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background: #e0e7ef;
            border-radius: 28px;
            transition: background .4s;
        }
        .switch .circle {
            position: absolute;
            top: 4px;
            left: 4px;
            width: 20px;
            height: 20px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            transition: left .4s;
        }
        .switch input:checked ~ .circle {
            left: 24px;
        }
    </style>
</head>
<body style="background-color: #e9ecf5;">
<main class="app-shell" role="main">
    <div class="container" style="max-width:430px;margin:0 auto;padding:0;margin-bottom:80px;">
        <div class="profile-title" style="font-size:28px;font-weight:bold;margin-bottom:20px;text-align:left;margin-left:0;">Profile</div>
        <div class="card" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);padding:24px 28px;margin-bottom:24px;background:#fff;position:relative;">
            <div class="athlete-avatar mb-2" style="margin-bottom: 18px;display:flex;justify-content:center;">
                <img src="{{ $athlete?->profile_pic && $athlete?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $athlete->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicView">
            </div>
            <div style="text-align:center; margin-bottom:10px;">
                <h5 style="font-size:20px;font-weight:600;">Athlete ID : {{ $athlete?->athlete_id ?? 'N/A' }}</h5>
            </div>
            <hr style="border:none; border-top:1.5px solid #e9ecf5; margin-bottom:18px; margin-top:0;">
            <div style="font-size:16px;line-height:1.7;color:#222;">
                <p><strong>Name:</strong> {{ $athlete?->name ?? ($name ?? '') }}</p>
                <p><strong>Email:</strong> {{ $athlete?->email ?? ($email ?? '') }}</p>
                <p><strong>Weight (kg):</strong> {{ $athlete?->weight ?? (isset($weight) ? $weight : '') }}</p>
                <p><strong>Height (cm):</strong> {{ $athlete?->height ?? (isset($height) ? $height : '') }}</p>
                <p><strong>BMI:</strong> {{ $athlete?->bmi ?? ((isset($weight) && isset($height)) ? round($weight / pow($height / 100, 2), 2) : '') }}</p>
                <p><strong>Sport:</strong> {{ $athlete?->sport ?? ($sport ?? '') }}</p>
                <p><strong>Training Intensity:</strong> {{ $athlete?->intensity ?? ($training_intensity ?? '') }}</p>
                <p><strong>Status:</strong> 
                    @if(($athlete?->status ?? $status ?? 'active') === 'inactive')
                        <span class="status-inactive">Inactive</span>
                    @else
                        <span class="status-active">Active</span>
                    @endif
                </p>
            </div>
            <div class="edit-profile-btn" style="text-align:right;margin-top:0;margin-bottom:-12px;position:absolute;bottom:24px;right:28px;">
                <a href="{{ route('profile.editprofile', $athlete?->athlete_id) }}" class="edit-profile-btn mt-3" style="text-decoration:none;">Edit</a>
            </div>
        </div>
        <div class="profile-card" style="margin-top: 0; background:rgb(255, 255, 255); border-radius: 12px; box-shadow: 0 2px 8px rgba(30,58,47,0.06); padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; max-width: 430px; margin-left: auto; margin-right: auto;">
            <span style="font-size: 1.1rem; font-weight: 600; color: #222;">Stay Logged In</span>
            <form method="POST" action="{{ route('athlete.stayloggedin', $athlete?->athlete_id) }}" id="stayLoggedInForm">
                @csrf
                <label class="switch" style="position:relative;display:inline-block;width:48px;height:28px;">
                    <input type="checkbox" id="stayLoggedInSwitch" name="stay_logged_in" value="1" {{ ($athlete?->stay_logged_in ?? false) ? 'checked' : '' }}>
                    <span class="slider"></span>
                    <span class="circle"></span>
                </label>
            </form>
        </div>
        <div class="profile-card">
            <label>Volume for Alerts</label>
            <input type="range" min="0" max="100" value="{{ $athlete?->alert_volume ?? 50 }}">
            <label style="margin-top:15px;">Volume for Reminders</label>
            <input type="range" min="0" max="100" value="{{ $athlete?->reminder_volume ?? 50 }}">
        </div>
        <div class="button-group" style="display:flex;justify-content:space-between;gap:16px;margin-top:24px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn-danger" style="background:#f5bcbc;color:#b30000;padding:8px 18px;border-radius:8px;border:none;font-weight:500;">Log Out</button>
            </form>
            <form method="GET" action="{{ route('profile.delete.confirm') }}">
                <button class="btn-danger" style="background:#f5bcbc;color:#b30000;padding:8px 18px;border-radius:8px;border:none;font-weight:500;">Delete Account</button>
            </form>
        </div>
    </div>
</main>
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