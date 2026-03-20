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