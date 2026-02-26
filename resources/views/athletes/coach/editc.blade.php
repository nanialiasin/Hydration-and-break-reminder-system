@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/coachprofile.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
@section('content')
<div class="edit-page">
    <div class="edit-container">
        <div class="edit-title">Edit Profile</div>
        <div class="edit-card">
            <form action="{{ route('coach.update', $coach->id) }}" method="POST">
                @csrf
                <div class="input-rectangle">
                    <input type="text" name="name" value="{{ $coach->name }}" class="form-control">
                </div>
                <div class="input-rectangle">
                    <input type="email" value="{{ $coach->email }}" class="form-control" disabled>
                    <small class="text-danger">Email cannot be changed.</small>
                </div>
                <div class="input-rectangle">
                    <input type="text" name="phone_number" value="{{ $coach->phone_number }}" class="form-control">
                </div>
                <div class="input-rectangle">
                    <input type="text" name="team_name" value="{{ $coach->team_name }}" class="form-control">
                </div>
                <div class="button-group">
                    <button class="btn btn-dark">Save</button>
                    <a href="{{ route('coach.profile', $coach->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection