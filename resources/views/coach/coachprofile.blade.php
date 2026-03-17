<!DOCTYPE html>
<html>
<head>
    <title>Coach Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
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
        .btn-danger {
            width: 200px;
            padding: 12px;
            border-radius: 15px;
            border: none;
            background-color: #f5bcbc;
            color: #b30000;
            font-weight: bold;
            cursor: pointer;
            display: block;
        }

        .btn-danger + .btn-danger {
            margin-top: 0;
        }

        .btn-danger:hover {
            background-color: #f29c9c;
        }
        
    </style>
</head>

<body style="background-color: #e9ecf5;">
<main role="main">
    <div class="container" style="max-width:430px;margin:0 auto;padding:0;">
        <div class="profile-title" style="font-size:28px;font-weight:bold;margin-bottom:20px;text-align:left;margin-left:0;">Profile</div>
        <div class="card" style="border-radius:16px;box-shadow:0 2px 12px rgba(0,0,0,0.08);padding:24px 28px;margin-bottom:24px;background:#fff;">
            <div class="coach-avatar mb-2" style="margin-bottom: 18px;display:flex;justify-content:center;">
                <img src="{{ $coach?->profile_pic && $coach?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $coach->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicView">
            </div>
            <div style="text-align:center; margin-bottom:10px;">
                <h5 style="font-size:20px;font-weight:600;">Coach ID : {{ $coach->coach_id ?? $coach->id }}</h5>
            </div>
            <hr style="border:none; border-top:1.5px solid #e9ecf5; margin-bottom:18px; margin-top:0;">
            <div style="font-size:16px;line-height:1.7;color:#222;">
                <p><strong>Name:</strong> {{ $coach->name }}</p>
                <p><strong>Email:</strong> {{ $coach->email }}</p>
                <p><strong>Sport:</strong> {{ $coach->sport }}</p>
                <p><strong>Phone Number:</strong> {{ $coach->phone_number }}</p>
                <p><strong>Team Name:</strong> {{ $coach->team_name }}</p>
            </div>
            <div style="text-align:right;margin-top:12px;">
                <a href="{{ route('coach.edit', $coach->id) }}" class="mt-3" style="text-decoration:none;">Edit</a>
            </div>
        </div>
        <div class="profile-card" style="margin-top: 0; background:rgb(255, 255, 255); border-radius: 12px; box-shadow: 0 2px 8px rgba(30,58,47,0.06); padding: 18px 24px; display: flex; justify-content: space-between; align-items: center; max-width: 430px; margin-left: auto; margin-right: auto;">
            <span style="font-size: 1.1rem; font-weight: 600; color: #222;">Stay Logged In</span>
            <form method="POST" action="{{ route('coach.stayloggedin', $coach->id) }}" id="stayLoggedInForm">
                @csrf
                <label class="switch" style="position:relative;display:inline-block;width:48px;height:28px;">
                    <input type="checkbox" name="stay_logged_in" value="1" {{ ($coach?->stay_logged_in ?? false) ? 'checked' : '' }} onchange="document.getElementById('stayLoggedInForm').submit();" style="opacity:0;width:0;height:0;">
                    <span class="slider"></span>
                    <span class="circle"></span>
                </label>
            </form>
        </div>
        <div class="button-group" style="display:flex;justify-content:space-between;gap:16px;margin-top:24px;margin-bottom:80px;">
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
</nav>
</body>
</html>
</div>