<!DOCTYPE html>
<html>
<head>
    <title>Coach Homepage</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, p, span {
            font-family: 'Poppins', Arial, sans-serif !important;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Welcome, Coach.</h1>

    <div class="training-card">
        Today's Training Time :
    </div>

    <div class="card">
        <div class="card-title">Team Overview :</div>
        <p>Total Athletes : {{ $totalAthletes }}</p>
        <p>Checked-in : {{ $checkedIn }}</p>
        <p>Not Checked-in : {{ $notCheckedIn }}</p>
    </div>

    <div class="card">
        <div class="card-title">Recent Activities :</div>
        <p>No recent activity.</p>
    </div>

</div>

<!-- <div class="bottom-nav">
    Home | Sessions | Add | Stats | Profile
</div> -->

</body>
</html>