<!DOCTYPE html>
<html>
<head>
    <title>Coach Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/coach-home.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<main class="app-shell" role="main">
    <div class="content">
        <h1 class="greeting">Welcome, Coach.</h1>

        <section class="stats-card">
            <div class="stat-row">
                <span class="stat-label">Total Athletes</span>
                <span class="stat-value">{{ $totalAthletes ?? 0 }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Active Athletes</span>
                <span class="stat-value">{{ $activeAthletes ?? 0 }}</span>
            </div>
            <div class="stat-row">
                <span class="stat-label">Inactive Athletes</span>
                <span class="stat-value">{{ $inactiveAthletes ?? 0 }}</span>
            </div>
        </section>

        <section class="summary-card">
            <h2 class="card-title">Team Summary</h2>
            <div class="summary-item">
                <span class="summary-label">Average Intensity</span>
                <span class="summary-value">{{ $averageIntensity ?? 'N/A' }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Last Updated</span>
                <span class="summary-value">Today</span>
            </div>
        </section>

        <section class="actions-card">
            <h2 class="card-title">Quick Actions</h2>
            <div class="action-buttons">
                <a href="{{ route('athletes.addathlete') }}" class="action-btn">Add Athlete</a>
                <a href="{{ route('session.create') }}" class="action-btn">Create Session</a>
            </div>
        </section>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="nav-item active" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="nav-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="nav-item" aria-label="Activity">
            <img src="{{ asset('images/Create.svg') }}" alt="Activity" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="nav-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="nav-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
