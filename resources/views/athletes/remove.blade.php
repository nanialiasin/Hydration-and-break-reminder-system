<!DOCTYPE html>
<html>
<head>
    <title>Remove Athlete</title>
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

        .status-active {
            color: green;
            font-weight: 600;
        }

        .status-inactive {
            color: red;
            font-weight: 600;
        }

        .remove-btn {
            background: #e10600;
        }

        .remove-btn:hover {
            background: #b30000;
        }
    </style>
</head>
<body>

<div class="container">

    <a href="{{ route('coach.creating') }}" class="back-button">←</a>

    <img src="{{ asset('images/hydrapulse-logo.svg') }}" 
         style="display:block; margin:0 auto 18px auto; width:180px; height:180px; border-radius:50%;">

    <h1>Remove Athlete</h1>

    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <form method="POST" action="{{ route('athletes.destroy.byid') }}">
        @csrf
        @method('DELETE')

        <input type="text" name="athlete_id" placeholder="Athlete ID">

        <div class="card">
            <div>Athlete Data</div>

            <input type="text" name="name" placeholder="Name :" readonly>
            <input type="text" name="sport" placeholder="Sport :" readonly>
            <input type="text" name="training_intensity" placeholder="Training Intensity :" readonly>
            <input type="text" name="status" placeholder="Status :" readonly id="statusField">
        </div>

        <button type="submit" class="remove-btn">Remove Athlete</button>

    </form>

    <div style="margin-top: 24px;">
        <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 12px; color: #111827;">Your Athletes</h2>
        @if(isset($coachAthletes) && $coachAthletes->count() > 0)
            <div style="display: flex; flex-direction: column; gap: 8px;">
                @foreach($coachAthletes as $athlete)
                    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; font-size: 14px; color: #111827;">{{ $athlete->name }}</div>
                            <div style="font-size: 12px; color: #6b7280;">ID: {{ $athlete->athlete_id }} • {{ $athlete->sport ?? 'N/A' }} • {{ $athlete->status ?? 'active' }}</div>
                        </div>
                        <form method="POST" action="{{ route('athletes.destroy.byid') }}" style="margin: 0;" onsubmit="return confirm('Remove this athlete from your list?');">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="athlete_id" value="{{ $athlete->athlete_id }}">
                            <button type="submit" class="remove-btn" style="padding: 8px 10px; font-size: 12px;">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px; color: #6b7280; font-size: 14px;">
                No athletes in your list.
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
                document.querySelector('input[name="name"]').value = data.name || '';
                document.querySelector('input[name="sport"]').value = data.sport || '';
                document.querySelector('input[name="training_intensity"]').value = data.training_intensity || '';

                const statusField = document.querySelector('#statusField');
                statusField.value = data.status || '';

                // Change text color based on status
                if (data.status === "Active") {
                    statusField.classList.remove('status-inactive');
                    statusField.classList.add('status-active');
                } else {
                    statusField.classList.remove('status-active');
                    statusField.classList.add('status-inactive');
                }
            }
        })
        .catch(() => {
            document.querySelectorAll('.card input').forEach(input => input.value = '');
        });
});
</script>

</body>
</html>