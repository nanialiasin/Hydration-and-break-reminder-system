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
                    <span class="stat-value"><?php echo e($temp ?? 32); ?>°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span class="stat-value"><?php echo e($humidity ?? 74); ?>%</span>
                </div>
            </div>

            <div class="sweat-risk">
                <span>HIGH SWEAT RISK</span>
            </div>

            <div class="timer-container">
                <div class="timer" id="sessionTimer">00:00:00</div>
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
        // Get the interval (minutes) passed from HydrationReminderController
        let intervalMinutes = <?php echo e($interval ?? 20); ?>; 
        
        let hours = 0;
        let minutes = intervalMinutes;
        let seconds = 0;

        const timerElement = document.getElementById('sessionTimer');

        // Function to format time string
        const formatTime = (h, m, s) => {
            return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
        };

        // Initialize display
        timerElement.textContent = formatTime(hours, minutes, seconds);

        // Timer functionality - Countdown to 0
        const countdown = setInterval(() => {
            if (seconds === 0) {
                if (minutes === 0) {
                    if (hours === 0) {
                        clearInterval(countdown);
                        // TRIGGER REMINDER LOGIC
                        alert("Time to hydrate! Drink water now.");
                        return;
                    }
                    hours--;
                    minutes = 59;
                } else {
                    minutes--;
                }
                seconds = 59;
            } else {
                seconds--;
            }
            
            timerElement.textContent = formatTime(hours, minutes, seconds);
        }, 1000);
    </script>
</body>
</html><?php /**PATH C:\Users\Acer\Hydration-and-break-reminder-system\resources\views/session.blade.php ENDPATH**/ ?>