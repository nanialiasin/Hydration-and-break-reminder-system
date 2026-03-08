@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/coachprofile.css') }}">
<link rel="stylesheet" href="{{ asset('css/editprofile.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
@section('content')
<div class="profile-page">
    <div class="profile-container">
        <div class="profile-title mb-3 text-start" style="text-align:left;">Edit Profile</div>
        <div class="profile-card" style="position:relative;display:flex;flex-direction:column;align-items:center;padding-bottom:32px;width:100%;max-width:400px;margin:0 auto;">
            <div style="width:100%;max-width:180px;display:flex;flex-direction:column;align-items:center;margin-bottom:18px;">
                <div class="coach-avatar mb-2" style="margin-bottom: 18px;display:flex;justify-content:center;width:100%;">
                    <img src="{{ $coach?->profile_pic && $coach?->profile_pic !== 'default.jpg' ? asset('storage/profile_pics/' . $coach->profile_pic) : asset('images/default.jpg') }}" class="rounded-circle" width="100" height="100" id="profilePicView" style="border: 4px solid #e0e7ef; border-radius: 50%; object-fit: cover;">
                </div>
                <form id="profile-pic-form" method="POST" action="{{ route('coach.updatePic', $coach->id) }}" enctype="multipart/form-data" style="text-align:center;width:100%;margin-bottom:10px;">
                    @csrf
                    <input type="file" name="profile_pic" id="profilePicInput" accept="image/*" style="display:none;">
                    <button type="button" class="btn btn-dark btn-sm mt-2" style="padding: 10px 32px; font-weight: 600; font-size: 16px; border-radius: 10px;" onclick="document.getElementById('profilePicInput').click();">Change Profile</button>
                    <button type="submit" class="btn btn-dark btn-sm mt-2" style="padding: 10px 32px; font-weight: 600; font-size: 16px; border-radius: 10px; display:none;" id="savePicBtn">Save</button>
                </form>
                <div class="coach-id" style="text-align:center;font-weight:600;font-size:18px;margin-bottom:0;width:100%;">Coach ID : {{ $coach->coach_id ?? $coach->id }}</div>
                <hr style="border:none; border-top:1.5px solid #e9ecf5; margin-bottom:8px; margin-top:0;">
            </div>
            <hr style="border:none; border-top:1.5px solid #e9ecf5; margin-bottom:18px; margin-top:0;">          
            <form action="{{ route('coach.update', ['id' => $coach->id]) }}" method="POST" style="width:100%;max-width:340px;">
                @csrf
                @method('POST')
                <div class="input-group">
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $coach->name }}" class="form-control mini-rectangle">
                </div>
                <div class="input-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ $coach->email }}" class="form-control mini-rectangle" readonly>
                    <br><small class="text-danger">Email cannot be changed.</small>
                </div>
                <div class="input-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone_number" value="{{ $coach->phone_number }}" class="form-control mini-rectangle">
                </div>
                <div class="input-group">
                    <label>Team Name</label>
                    <input type="text" name="team_name" value="{{ $coach->team_name }}" class="form-control mini-rectangle">
                </div>
                <div class="button-group" style="display:flex;justify-content:center;gap:16px;margin-top:32px;">
                    <button class="btn btn-dark">Save</button>
                    <a href="{{ route('coach.profile') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
<style>
.input-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 18px;
}
.mini-rectangle {
    border-radius: 10px;
    padding: 10px 16px;
    background: #f7faff;
    border: 1.5px solid #e0e7ef;
    font-size: 16px;
    box-shadow: 0 1px 4px rgba(30,58,47,0.06);
    margin-top: 6px;
}
</style>
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
</script>