<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hydration Alert</title>
    @vite('resources/css/hydration-alert.css')
</head>
<body>
    <main class="alert-shell" role="main">
        <section class="alert-card" aria-label="Hydration alert">
            <div class="visual-wrap">
                <img src="{{ asset('images/hydrate-time.svg') }}" alt="Hydrate time" class="hydrate-visual" width="280" height="280">
            </div>

            <div class="actions">
                <a href="{{ route('session.show', ['hydration_action' => 'followed']) }}" class="btn btn-drink">Drink Now</a>
                <a href="{{ route('session.show', ['hydration_action' => 'snoozed']) }}" class="btn btn-snooze">Snooze</a>
            </div>
        </section>
    </main>
</body>
</html>
