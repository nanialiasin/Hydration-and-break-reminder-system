@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/athlprofile.css') }}">
<link rel="stylesheet" href="{{ asset('css/editprofile.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,500,600&display=swap" rel="stylesheet">
@section('content')
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-title mb-3 text-start" style="text-align:left;">Edit Profile</div>
        <div class="profile-card" style="position:relative;display:flex;flex-direction:column;align-items:center;padding-bottom:32px;width:100%;max-width:400px;margin:0 auto;">
            <div style="width:100%;max-width:180px;display:flex;flex-direction:column;align-items:center;margin-bottom:18px;">
                <div class="profile-avatar mb-2" style="margin-bottom: 18px;display:flex;justify-content:center;width:100%;">
                    <img src="{{ $athlete?->profile_pic && $athlete?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $athlete->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicPreview">
                </div>
                <form id="profile-pic-form" method="POST" action="{{ route('profile.updatePic') }}" enctype="multipart/form-data" style="text-align:center;width:100%;margin-bottom:10px;">
                    @csrf
                    <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display:none;">
                    <button type="button" class="btn btn-dark btn-sm mt-2" style="padding: 10px 32px; font-weight: 600; font-size: 16px; border-radius: 10px;" onclick="document.getElementById('profilePicInput').click();">Change Profile</button>
                    <button type="submit" class="btn btn-dark btn-sm mt-2" style="padding: 10px 32px; font-weight: 600; font-size: 16px; border-radius: 10px; display:none;" id="savePicBtn">Save</button>
                </form>
                <div class="athlete-id" style="text-align:center;font-weight:600;font-size:18px;margin-bottom:0;width:100%;">Athlete ID : {{ $athlete?->athlete_id ?? 'N/A' }}</div>
            </div>
            <form method="POST" action="{{ route('athlete.profile.update', $athlete?->athlete_id) }}" style="width:100%;max-width:340px;">
                @csrf
                @method('PUT')
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ old('name', $athlete?->name ?? Auth::user()->name ?? '') }}" class="form-control" style="width:100%;min-width:260px;max-width:340px;">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" value="{{ $athlete?->email ?? Auth::user()->email ?? '' }}" class="form-control" style="width:100%;min-width:260px;max-width:340px;" readonly>
                    <br><small class="text-danger">Email cannot be changed.</small>
                </div>
                <div class="input-group">
                    <label>Weight (kg)</label>
                    <input type="number" name="weight" value="{{ old('weight', $athlete?->weight ?? Auth::user()->weight ?? '') }}" class="form-control" style="width:100%;min-width:260px;max-width:340px;">
                </div>
                <div class="input-group">
                    <label>Height (cm)</label>
                    <input type="number" name="height" value="{{ old('height', $athlete?->height ?? Auth::user()->height ?? '') }}" class="form-control" style="width:100%;min-width:260px;max-width:340px;">
                </div>
                <div class="input-group">
                    <label>Status</label>
                    <select name="status" class="form-control" style="width:100%;min-width:260px;max-width:340px;">
                        <option value="active" {{ (old('status', $athlete?->status ?? 'active') == 'active') ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ (old('status', $athlete?->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="input-group">
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
                <div class="button-group" style="display:flex;justify-content:center;gap:16px;margin-top:32px;">
                    <button type="submit" class="btn btn-dark">Save</button>
                    <a href="{{ route('profile.athlprofile', $athlete?->athlete_id) }}" class="btn btn-secondary">Cancel</a>
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