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

    <a href="{{ url()->previous() }}" class="back-button">←</a>

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