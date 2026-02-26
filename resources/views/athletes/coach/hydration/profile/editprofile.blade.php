@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/athlprofile.css') }}">
<link rel="stylesheet" href="{{ asset('css/editprofile.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
@section('content')
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-title mb-3 text-start" style="text-align:left;">Edit Profile</div>
        <div class="profile-card">
            <div class="profile-avatar mb-2" style="margin-bottom: 18px;">
                <img src="{{ $athlete?->profile_pic && $athlete?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $athlete->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicPreview">
            </div>

            <div class="athlete-id">
                Athlete ID : {{ $athlete?->athlete_id ?? 'N/A' }}
            </div>

            <form id="profile-pic-form" method="POST" action="{{ route('profile.updatePic') }}" enctype="multipart/form-data" style="margin: 0 auto 18px auto; text-align:center;">
                @csrf
                <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display:none;">
                <button type="button" class="btn btn-dark btn-sm mt-2" style="padding: 10px 32px; font-weight: 600; font-size: 16px; border-radius: 10px;" onclick="document.getElementById('profilePicInput').click();">Change Profile</button>
                <button type="submit" class="btn btn-dark btn-sm mt-2" style="padding: 10px 32px; font-weight: 600; font-size: 16px; border-radius: 10px; display:none;" id="savePicBtn">Save</button>
            </form>

            <form method="POST" action="{{ route('athlete.profile.update') }}">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $athlete?->name ?? Auth::user()->name ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label>Email</label>
                            <input type="email" class="form-control" value="{{ $athlete?->email ?? Auth::user()->email ?? '' }}" disabled>
                            <br><small class="text-danger">Email cannot be changed.</small>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-6 mb-4">
                            <label>Weight (kg)</label>
                            <input type="number" name="weight" class="form-control" value="{{ old('weight', $athlete?->weight ?? Auth::user()->weight ?? '') }}">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label>Height (cm)</label>
                            <input type="number" name="height" class="form-control" value="{{ old('height', $athlete?->height ?? Auth::user()->height ?? '') }}">
                        </div>
                    </div>
                </div>
                <div class="mb-4 text-center">
                    <label class="mb-3">Training Intensity</label>
                    <div class="intensity-group d-flex justify-content-center gap-5">
                        <div class="d-flex flex-column align-items-center mx-3">
                            <input type="radio" name="intensity" value="Beginner" {{ (old('intensity', $athlete?->intensity ?? Auth::user()->intensity ?? '') == 'Beginner') ? 'checked' : '' }}>
                            <span class="mt-2">Beginner</span>
                        </div>
                        <div class="d-flex flex-column align-items-center mx-3">
                            <input type="radio" name="intensity" value="Intermediate" {{ (old('intensity', $athlete?->intensity ?? Auth::user()->intensity ?? '') == 'Intermediate') ? 'checked' : '' }}>
                            <span class="mt-2">Intermediate</span>
                        </div>
                        <div class="d-flex flex-column align-items-center mx-3">
                            <input type="radio" name="intensity" value="Advanced" {{ (old('intensity', $athlete?->intensity ?? Auth::user()->intensity ?? '') == 'Advanced') ? 'checked' : '' }}>
                            <span class="mt-2">Advanced</span>
                        </div>
                    </div>
                </div>
                <div class="button-group mt-4">
                    <a href="{{ route('profile.athlprofile') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-dark">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
@endsection