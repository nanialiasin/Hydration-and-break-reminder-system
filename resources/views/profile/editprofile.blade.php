<!DOCTYPE html>
<html>
<head>
    <title>Edit Athlete Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/editprofile.css') }}?v={{ filemtime(public_path('css/editprofile.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="profile-title">Edit Profile</h1>

        <section class="card profile-card">
            @if ($errors->any())
                <div class="alert" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="athlete-avatar">
                <img src="{{ $athlete?->profile_pic && $athlete?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $athlete->profile_pic) : asset('images/default.jpg') }}" class="avatar" width="100" height="100" id="profilePicPreview">
                <div class="pic-upload-section">
                    <form id="profile-pic-form" method="POST" action="{{ route('profile.updatePic') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" class="hidden-input">
                        <button type="button" class="btn-change-pic" onclick="document.getElementById('profilePicInput').click();">Change Photo</button>
                        <button type="submit" class="btn-save-pic" id="savePicBtn" style="display:none;">Save Photo</button>
                    </form>
                </div>
            </div>

            <div class="athlete-id-wrap">
                <p class="athlete-id">Athlete ID: {{ $athlete?->athlete_id ?? 'N/A' }}</p>
            </div>

            <form id="edit-form" method="POST" action="{{ route('athlete.profile.update', $athlete?->athlete_id) }}" class="edit-form">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $athlete?->name ?? Auth::user()->name ?? '') }}" class="form-field" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" value="{{ $athlete?->email ?? Auth::user()->email ?? '' }}" class="form-field readonly-field" readonly>
                    <small class="text-danger">Email cannot be changed.</small>
                </div>

                <div class="form-group">
                    <label for="weight">Weight (kg)</label>
                    <input type="number" id="weight" name="weight" value="{{ old('weight', $athlete?->weight ?? Auth::user()->weight ?? '') }}" class="form-field">
                </div>

                <div class="form-group">
                    <label for="height">Height (cm)</label>
                    <input type="number" id="height" name="height" value="{{ old('height', $athlete?->height ?? Auth::user()->height ?? '') }}" class="form-field">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-field">
                        <option value="active" {{ (old('status', $athlete?->status ?? 'active') == 'active') ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ (old('status', $athlete?->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Training Intensity</label>
                    <div class="intensity-group">
                        <label><input type="radio" name="intensity" value="Beginner" {{ (old('intensity', $athlete?->intensity ?? Auth::user()->intensity ?? '') == 'Beginner') ? 'checked' : '' }} required> Beginner</label>
                        <label><input type="radio" name="intensity" value="Intermediate" {{ (old('intensity', $athlete?->intensity ?? Auth::user()->intensity ?? '') == 'Intermediate') ? 'checked' : '' }} required> Intermediate</label>
                        <label><input type="radio" name="intensity" value="Advanced" {{ (old('intensity', $athlete?->intensity ?? Auth::user()->intensity ?? '') == 'Advanced') ? 'checked' : '' }} required> Advanced</label>
                    </div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('profile.athlprofile', $athlete?->athlete_id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </section>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('home') }}" class="nav-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('training') }}" class="nav-item" aria-label="Training">
            <img src="{{ asset('images/Training Button.svg') }}" alt="Training" width="24" height="24">
        </a>
        <a href="{{ route('history') }}" class="nav-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('profile.athlprofile', $athlete?->athlete_id) }}" class="nav-item active" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</main>
<script>
    document.getElementById('profilePicInput').addEventListener('change', function(e) {
        const [file] = e.target.files;
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                document.getElementById('profilePicPreview').src = event.target.result;
                document.getElementById('savePicBtn').style.display = 'inline-block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
</body>
</html>