<!DOCTYPE html>
<html>
<head>
    <title>Coach Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/coach-profile.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="profile-title">Profile</h1>

        <section class="card profile-card">
            <div class="coach-avatar">
                <img src="{{ asset('storage/profile_pics/' . $coach->profile_pic) }}">
            </div>
            <div class="coach-id-wrap">
                <h2 class="coach-id">Coach ID: {{ $coach->coach_id ?? 'N/A' }}</h2>
            </div>

            <div class="profile-info">
                <div class="info-row"><span>Name</span><strong>{{ $coach->name ?? 'N/A' }}</strong></div>
                <div class="info-row"><span>Email</span><strong>{{ $coach->email ?? 'N/A' }}</strong></div>
                <div class="info-row"><span>Sport</span><strong>{{ $coach->sport ?? 'N/A' }}</strong></div>
                <div class="info-row"><span>Phone Number</span><strong>{{ $coach->phone_number ?? 'N/A' }}</strong></div>
                <div class="info-row"><span>Team Name</span><strong>{{ $coach->team_name ?? 'N/A' }}</strong></div>
                <div class="info-row"><span>Status</span><strong class="status-active">Active</strong></div>
            </div>

            <div class="edit-profile-row">
                <a href="{{ route('coach.edit', $coach->id) }}" class="edit-profile-btn">Edit Profile</a>
            </div>
        </section>

        <section class="card toggle-card">
            <span class="toggle-label">Stay Logged In</span>
            <form method="POST" action="{{ route('coach.stayloggedin', $coach->id) }}" id="stayLoggedInForm">
                @csrf
                <label class="switch">
                    <input type="checkbox" id="stayLoggedInSwitch" name="stay_logged_in" value="1" {{ ($coach?->stay_logged_in ?? false) ? 'checked' : '' }}>
                    <span class="slider"></span>
                </label>
            </form>
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
        <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="navi-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="navi-item" aria-label="Activity">
            <img src="{{ asset('images/Create.svg') }}" alt="Activity" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="navi-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="navi-item active" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
<!-- <nav class="nav-bar" aria-label="Main navigation" style="position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:min(430px,100vw);z-index:1000;display:flex;justify-content:space-between;align-items:center;background:#000;border-radius:16px 16px 0 0;box-shadow:0 -2px 12px rgba(0,0,0,0.08);padding:10px 32px 8px 32px;max-width:100vw;border-top:1.5px solid #e0e7ef;margin:0;">
    <a href="{{ route('coach.home') }}" class="navi-item" aria-label="Home">
        <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
    </a>
    <a href="{{ route('hydration.index') }}" class="navi-item" aria-label="Hydration">
        <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
    </a>
    <a href="{{ route('coach.creating') }}" class="navi-item" aria-label="Create">
        <img src="{{ asset('images/Create.svg') }}" alt="Create" width="24" height="24">
    </a>
    <a href="{{ route('coach.chistory') }}" class="navi-item" aria-label="History">
        <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
    </a>
    <a href="{{ route('coach.profile') }}" class="navi-item active" aria-label="Profile">
        <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
    </a>
</nav> -->
</body>
</html>