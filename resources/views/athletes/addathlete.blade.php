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

    <a href="{{ url()->previous() }}" class="back-button">
    ←
    </a>

    <img src="{{ asset('images/hydrapulse-logo.png') }}" alt="Hydrapulse Logo" style="display:block; margin:0 auto 18px auto; width:180px; height:180px; border-radius:50%;">
    <h1>Add Athlete Profile</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('athletes.store') }}">
        @csrf

        <input type="text" name="athlete_id" placeholder="Athlete ID">

        <div class="card">
            <div style="font-weight:600; margin-bottom:8px;">Athlete Data</div>
            <input type="text" name="name" placeholder="Name :" readonly>
            <input type="text" name="sport" placeholder="Sport :" readonly>
            <input type="text" name="training_intensity" placeholder="Training Intensity :" readonly>
            <input type="text" name="status" placeholder="Status :" readonly>
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
                document.querySelector('input[name="name"]').value = data.name || '';
                document.querySelector('input[name="sport"]').value = data.sport || '';
                document.querySelector('input[name="training_intensity"]').value = data.training_intensity || '';
                document.querySelector('input[name="status"]').value = data.status || '';
            }
        })
        .catch(() => {
            // Optionally clear fields if not found
            document.querySelector('input[name="name"]').value = '';
            document.querySelector('input[name="sport"]').value = '';
            document.querySelector('input[name="training_intensity"]').value = '';
            document.querySelector('input[name="status"]').value = '';
        });
});
</script>

</body>
</html>