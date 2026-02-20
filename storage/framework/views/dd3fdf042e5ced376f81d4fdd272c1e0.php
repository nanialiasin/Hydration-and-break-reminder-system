<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Active Session</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/session.css'); ?>
</head>
<body>
    <div class="app-shell">
        <div class="header">
            <a href="<?php echo e(route('home')); ?>" class="back-button" aria-label="Go back">
                <span>←</span>
            </a>
            <h1>Session</h1>
        </div>

        <div class="content">
            <div class="stats-card">
                <div class="stat-row">
                    <span class="stat-label">Temperature</span>
                    <span class="stat-value">32°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span class="stat-value">74%</span>
                </div>
            </div>

            <div class="sweat-risk">
                <span>HIGH SWEAT RISK</span>
            </div>

            <div class="timer-container">
                <div class="timer" id="sessionTimer">00:08:54</div>
            </div>

            <form method="POST" action="<?php echo e(route('session.end')); ?>" class="session-actions">
                <?php echo csrf_field(); ?>
                <button type="submit" class="btn btn-end">End Session</button>
            </form>
        </div>

        <nav class="bottom-nav" aria-label="Main navigation">
            <a href="<?php echo e(route('home')); ?>" class="nav-item" aria-label="Home">
                <img src="<?php echo e(asset('images/Home Button.png')); ?>" alt="Home" width="24" height="24">
            </a>
            <a href="#" class="nav-item" aria-label="Training">
                <img src="<?php echo e(asset('images/Training Button.svg')); ?>" alt="Training" width="24" height="24">
            </a>
            <a href="<?php echo e(route('session.create')); ?>" class="nav-item" aria-label="Create">
                <img src="<?php echo e(asset('images/Create.svg')); ?>" alt="Create" width="24" height="24">
            </a>
            <a href="<?php echo e(route('history')); ?>" class="nav-item" aria-label="History">
                <img src="<?php echo e(asset('images/History Button.svg')); ?>" alt="History" width="24" height="24">
            </a>
            <a href="#" class="nav-item" aria-label="Profile">
                <img src="<?php echo e(asset('images/Account Button.svg')); ?>" alt="Account" width="24" height="24">
            </a>
        </nav>
    </div>

    <script>
        // Timer functionality - increment from the initial time
        const timerElement = document.getElementById('sessionTimer');
        let hours = 0, minutes = 8, seconds = 54;

        setInterval(() => {
            seconds++;
            if (seconds === 60) {
                seconds = 0;
                minutes++;
            }
            if (minutes === 60) {
                minutes = 0;
                hours++;
            }
            const timeStr = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            timerElement.textContent = timeStr;
        }, 1000);
    </script>
</body>
</html>
<?php /**PATH C:\Users\CHC PC\Herd\25s2-g15\resources\views/session.blade.php ENDPATH**/ ?>