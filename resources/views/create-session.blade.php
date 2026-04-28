<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Session</title>
    @vite('resources/css/create-session.css')
</head>
<body>
    <div class="app-shell">
        <div class="content">
            <form class="session-form" method="POST" action="{{ route('session.store') }}">
                @csrf
                
                <div class="form-group">
                    <label for="sport">Sport</label>
                    <select id="sport" name="sport" class="form-select" required>
                        <option value="" disabled selected>Sports...</option>
                        <option value="badminton">Badminton</option>
                        <option value="basketball">Basketball</option>
                        <option value="cycling">Cycling</option>
                        <option value="football">Football</option>
                        <option value="jogging">Jogging</option>
                        <option value="running">Running</option>
                        <option value="swimming">Swimming</option>
                        <option value="tennis">Tennis</option>
                        <option value="walking">Walking</option>
                        <option value="yoga">Yoga</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="intensity">Intensity</label>
                    <select id="intensity" name="intensity" class="form-select" required>
                        <option value="beginner" selected>Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Duration</label>
                    <div class="duration-inputs">
                        <select id="hours" name="hours" class="form-select duration-select" required>
                            <option value="" disabled selected>Hour</option>
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                        </select>
                        <select id="minutes" name="minutes" class="form-select duration-select" required>
                            <option value="" disabled selected>Minutes</option>
                            <option value="0">0</option>
                            <option value="15">15</option>
                            <option value="30">30</option>
                            <option value="45">45</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('coach.creating') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-create">Create</button>
                </div>
            </form>
        </div>
    </div>

    <nav class="bottom-nav" aria-label="Main navigation">
        <a href="{{ route('coach.home') }}" class="nav-item" aria-label="Home">
            <img src="{{ asset('images/Home Button.png') }}" alt="Home" width="24" height="24">
        </a>
        <a href="{{ route('hydration.index') }}" class="nav-item" aria-label="Hydration">
            <img src="{{ asset('images/droplet.png') }}" alt="Hydration" width="24" height="24">
        </a>
        <a href="{{ route('coach.creating') }}" class="nav-item active" aria-label="Create">
            <img src="{{ asset('images/Create.svg') }}" alt="Create" width="24" height="24">
        </a>
        <a href="{{ route('coach.sessions.progress') }}" class="nav-item" aria-label="History">
            <img src="{{ asset('images/History Button.svg') }}" alt="History" width="24" height="24">
        </a>
        <a href="{{ route('coach.profile') }}" class="nav-item" aria-label="Profile">
            <img src="{{ asset('images/Account Button.svg') }}" alt="Account" width="24" height="24">
        </a>
    </nav>
</body>
</html>
