<!DOCTYPE html>
<html>
<head>
    <title>Edit Coach Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/coach-edit.css') }}?v={{ filemtime(public_path('css/coach-edit.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="profile-title">Edit Profile</h1>

        @if (session('success'))
            <div class="alert alert-success" role="alert" style="margin-bottom: 14px; color: #166534; background: #dcfce7; border: 1px solid #86efac; border-radius: 10px; padding: 10px 12px; font-weight: 600;">
                android {
                    // ... existing code ...
                    
                    buildTypes {
                        debug {
                            applicationIdSuffix ".debug"
                        }
                    }
                    
                    applicationVariants.all { variant ->
                        variant.outputs.all { output ->
                            outputFileName = "HydraPulse-${variant.baseName}.apk"
                        }
                    }
                }                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger" role="alert" style="margin-bottom: 14px; color: #991b1b; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 10px 12px; font-weight: 600;">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert" style="margin-bottom: 14px; color: #991b1b; background: #fee2e2; border: 1px solid #fca5a5; border-radius: 10px; padding: 10px 12px; font-weight: 600;">
                <ul style="margin: 0; padding-left: 18px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card profile-card">
            <div class="coach-avatar">
                <img src="{{ $coach?->profile_pic && $coach?->profile_pic !== 'default.jpg' ? route('profile.image', ['filename' => $coach->profile_pic]) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicView">
                <div class="pic-upload-section">
                    <form id="profile-pic-form" method="POST" action="{{ route('coach.updatePic', $coach->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display:none;">
                        <button type="button" class="btn-change-pic" onclick="document.getElementById('profilePicInput').click();">Change Profile Picture</button>
                        <button type="submit" class="btn-save-pic" id="savePicBtn" style="display:none;">Save Picture</button>
                    </form>
                </div>
            </div>

            <div class="coach-id-wrap">
                <h2 class="coach-id">Coach ID: {{ $coach->coach_id }}</h2>
            </div>

            <form id="edit-form" action="{{ route('coach.update', $coach->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ $coach->name }}" required class="form-field">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $coach->email }}" readonly class="form-field readonly-field">
                    <small class="text-muted">Email cannot be changed</small>
                </div>

                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" id="phone_number" name="phone_number" value="{{ $coach->phone_number }}" required class="form-field" pattern="[0-9]*" inputmode="numeric">
                </div>

                <div class="form-group">
                    <label for="team_name">Team Name</label>
                    <input type="text" id="team_name" name="team_name" value="{{ $coach->team_name }}" class="form-field">
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('coach.profile') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </section>
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
<script>
document.getElementById('profilePicInput').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profilePicView').src = e.target.result;
        };
        reader.readAsDataURL(file);
        document.getElementById('savePicBtn').style.display = 'inline-block';
    }
});

document.getElementById('phone_number').addEventListener('input', function (e) {
    this.value = this.value.replace(/[^0-9]/g, '');
});
</script>
</body>
</html>