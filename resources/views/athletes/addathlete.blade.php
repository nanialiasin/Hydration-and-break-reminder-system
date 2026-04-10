<!DOCTYPE html>
<html>
<head>
    <title>Add Athlete Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/addathlete.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
        body {
            background-color: #4a4d54 !important;
        }
    </style>
</head>
<body>

<div class="container">

    <a href="{{ route('coach.creating') }}" class="back-button">
    ←
    </a>

    <img src="{{ asset('images/hydrapulse-logo.svg') }}" alt="Hydrapulse Logo" style="display:block; margin:0 auto 18px auto; width:180px; height:180px; border-radius:50%;">
    <h1>Add Athlete Profile</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div class="alert alert-danger" style="margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('coach.addathlete.store') }}">
        @csrf
        <input type="hidden" name="created_by_coach" value="{{ Auth::check() ? Auth::user()->id : '' }}">
        <input type="text" name="athlete_id" placeholder="Athlete ID">

        <div class="card">
            <div style="font-weight:600; margin-bottom:8px;">Athlete Data</div>
            <input type="text" name="name" placeholder="Name :" id="athleteName">
            <input type="text" name="sport" placeholder="Sport :" id="athleteSport">
            <input type="text" name="training_intensity" placeholder="Training Intensity :" id="athleteIntensity">
            <input type="text" name="status" placeholder="Status :" id="athleteStatus">
        </div>

        <button type="submit">Confirm Add</button>

    </form>

    <div style="margin-top: 32px;">
        <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: white;">Available Athletes</h2>
        @if(isset($availableAthletes) && $availableAthletes && $availableAthletes->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($availableAthletes as $athlete)
                    <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 14px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; font-size: 14px; margin-bottom: 6px;">{{ $athlete->name }}</div>
                            <div style="font-size: 12px; color: #666;">ID: {{ $athlete->athlete_id }} • {{ $athlete->sport ?? 'N/A' }} • {{ $athlete->status ?? 'active' }}</div>
                        </div>
                        <form action="{{ route('athletes.add.byid') }}" method="POST" style="margin: 0;">
                            @csrf
                            <input type="hidden" name="athlete_id" value="{{ $athlete->athlete_id }}">
                            <button type="submit" style="background: #007bff; color: white; border: none; border-radius: 6px; padding: 8px 14px; font-size: 12px; font-weight: 600; cursor: pointer;">Add</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: white; border: 1px solid #ddd; border-radius: 10px; padding: 14px; color: #666; font-size: 14px;">
                No untaken athletes available right now.
            </div>
        @endif
    </div>

</div>

<script>
document.querySelector('input[name="athlete_id"]').addEventListener('blur', function() {
    const athleteId = this.value.trim();
    if (!athleteId) return;

    fetch(`/athletes/fetch/${athleteId}`)
        .then(response => {
            if (!response.ok) throw new Error('Not found');
            return response.json();
        })
        .then(data => {
            if (data) {
                document.getElementById('athleteName').value = data.name || '';
                document.getElementById('athleteSport').value = data.sport || '';
                document.getElementById('athleteIntensity').value = data.intensity || '';
                document.getElementById('athleteStatus').value = data.status || '';
            }
        })
        .catch(() => {
            // Optionally clear fields if not found
            document.getElementById('athleteName').value = '';
            document.getElementById('athleteSport').value = '';
            document.getElementById('athleteIntensity').value = '';
            document.getElementById('athleteStatus').value = '';
        });
});
</script>

</body>
</html>