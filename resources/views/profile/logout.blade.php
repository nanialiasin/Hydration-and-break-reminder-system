<!DOCTYPE html>
<html>
<head>
    <title>Log Out of Account</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/delete.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
    </style>
</head>
<body>
    <div class="modal-overlay">
        <div class="modal-card">
            <div class="warning-icon">⚠️</div>
            <h2>Are you sure you want to continue logging out?</h2>
            <p>This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="cancel-btn" onclick="window.location.href='{{ url()->previous() }}'">Cancel</button>
            <form id="confirmForm" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="continue-btn">Continue</button>
            </form>
            </div>
        </div>
    </div>
</body>
</html>