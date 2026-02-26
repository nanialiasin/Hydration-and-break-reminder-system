<head>
    <title>Athlete Activity</title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
    </style>
</head>
<body style="background: #e6f0fa; min-height: 100vh; margin: 0;">
    <h1 style="width: 395px; margin: 0 auto 24px auto; padding-top: 32px; font-size: 2.5rem; font-weight: bold; color: #222; letter-spacing: 1px;">Athlete Activity</h1>
    <div style="width: 395px; margin: 40px auto; padding: 32px; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.08);">
        <h2 style="margin-bottom: 16px; font-size: 2rem; font-weight: bold; letter-spacing: 1px;">Team Overview:</h2>
        <hr style="border: none; border-top: 1.5px solid #e0e7ef; margin-bottom: 32px; margin-top: 0;">
        @if($totalAthletes == 0)
            <p>No athletes added yet.</p>
        @else
            <p>Active Athletes: {{ $activeAthletes }}</p>
            <p>Inactive Athletes: {{ $inactiveAthletes }}</p>
            <p>Total Athletes: {{ $totalAthletes }}</p>
        @endif

        @if($averageIntensity)
            <p>Average Training Intensity:
                <span style="color: blue;">
                    {{ ucfirst($averageIntensity) }}
                </span> 
            </p>
        @else
            <p>Average Training Intensity: Not Available</p>
        @endif

        <div style="display: flex; gap: 16px; justify-content: center; margin-top: 32px;">
            <a href="{{ route('athletes.addathlete') }}" style="padding: 10px 24px; background:rgb(0, 0, 0); color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s; text-decoration: none; display: inline-block;">Add Athlete</a>
            <button style="padding: 10px 24px; background:rgb(134, 134, 134); color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">Remove Athlete</button>
        </div>
    </div>

    <div style="width: 395px; margin: 32px auto 0 auto; padding: 32px; background: #fff; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.04);">
        <h3 style="margin-bottom: 16px; font-size: 2rem; font-weight: bold; letter-spacing: 0.5px;">Session:</h3>
        <hr style="border: none; border-top: 1.5px solid #e0e7ef; margin-bottom: 24px; margin-top: 0;">
        @if($session && $session->sport)
            <div>
                <p>Sport: {{ $session->sport }}</p>
                <p>Beginner: {{ $session->beginner_duration }}</p>
                <p>Intermediate: {{ $session->intermediate_duration }}</p>
                <p>Advanced: {{ $session->advanced_duration }}</p>
            </div>
        @else
            <p>No session created yet.</p>
        @endif
        <div style="display: flex; justify-content: center; margin-top: 32px;">
            <button style="padding: 10px 24px; background:rgb(0, 0, 0); color: #fff; border: none; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">Create New Session</button>
        </div>
    </div>
</body>