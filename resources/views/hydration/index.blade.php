<!DOCTYPE html>
<html>
<head>
    <title>Hydration Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/hydration.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
    </style>
</head>

<body>
<main role="main">
<div class="center-viewport">
    <div class="container py-5" style="max-width: 900px;">
        <div class="settings-container">
            <h2 class="mb-4">Hydration Settings</h2>
            <div class="row justify-content-center">
                @foreach($settings as $setting)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100 rounded-4">
                            <div class="card-body d-flex flex-column">
                                <div class="intensity-title mb-3 text-center">{{ ucfirst($setting->intensity) }}</div>
                                <ul class="list-unstyled flex-grow-1">
                                    <li class="mb-2"><strong>Hydration reminder every:</strong> {{ $setting->hydration_reminder }} minutes</li>
                                    <li class="mb-2"><strong>Break duration:</strong> {{ $setting->break_duration }} minutes</li>
                                    <li class="mb-2"><strong>Break reminder every:</strong> {{ $setting->break_reminder }} minutes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('hydration.edit') }}" class="btn dark-btn px-4 py-2 rounded-pill">
                    Edit
                </a>
            </div>
        </div>
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
    <a href="#" class="navi-item active" aria-label="History">
        <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
    </a>
    <a href="{{ route('coach.profile') }}" class="navi-item" aria-label="Profile">
        <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
    </a>
</nav>
</body>
</html>