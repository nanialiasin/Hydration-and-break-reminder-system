@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/coachprofile.css') }}">
<link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
@section('content')
<div class="container">

    <h2>Profile</h2>

    <div class="card p-4 rounded-4">

        <h5>Coach ID : {{ $coach->id }}</h5>

        <p><strong>Name:</strong> {{ $coach->name }}</p>
        <p><strong>Email:</strong> {{ $coach->email }}</p>
        <p><strong>Sport:</strong> {{ $coach->sport }}</p>
        <p><strong>Phone Number:</strong> {{ $coach->phone_number }}</p>
        <p><strong>Team Name:</strong> {{ $coach->team_name }}</p>

        <a href="{{ route('coach.edit', $coach->id) }}" class="btn btn-primary mt-3">Edit</a>
    </div>

</div>
@endsection