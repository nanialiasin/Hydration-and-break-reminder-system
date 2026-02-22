<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/home.css'); ?>
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <h1 class="greeting">Welcome, User.</h1>

            <section class="stats-card">
                <div class="stat-row">
                    <span class="stat-label">Temperature</span>
                    <span class="stat-value"><?php echo e($temp ?? 32); ?>°C</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Humidity</span>
                    <span class="stat-value"><?php echo e($humidity ?? 74); ?>%</span>
                </div>
            </section>

            <section class="timer-card">
                <h2 class="timer-title">Keep your streak!</h2>
                
                <div class="timer-ring">
                    <svg viewBox="0 0 200 200" class="progress-ring">
                        <circle cx="100" cy="100" r="85" class="ring-bg"></circle>
                        <circle id="ringProgress" cx="100" cy="100" r="85" class="ring-progress" style="stroke-dasharray: 534; stroke-dashoffset: 0;"></circle>
                    </svg>
                    <div class="timer-text">
                        <p class="timer-label">Next drink in :</p>
                        <p id="homeCountdown" class="timer-countdown">00:00</p>
                    </div>
                </div>

                <button class="drink-btn">Drink Now</button>
            </section>
        </div>

        <nav class="bottom-nav" aria-label="Main navigation">
            <a href="#" class="nav-item active" aria-label="Home">
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
    </main>

    <script>
        const totalMinutes = <?php echo e($interval ?? 12); ?>;
        let totalSeconds = totalMinutes * 60;
        const initialSeconds = totalSeconds;

        const countdownElement = document.getElementById('homeCountdown');
        const ringProgress = document.getElementById('ringProgress');
        const circumference = 534; 

        function updateTimer() {
            const mins = Math.floor(totalSeconds / 60);
            const secs = totalSeconds % 60;
            
            // Update text
            countdownElement.textContent = `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;

            // Update ring visual
            const offset = circumference - (totalSeconds / initialSeconds) * circumference;
            ringProgress.style.strokeDashoffset = offset;

            if (totalSeconds <= 0) {
                clearInterval(timerInterval);
                alert("Reminder: Time to hydrate!"); // Trigger Reminder Logic
            } else {
                totalSeconds--;
            }
        }

        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer();
    </script>
</body>
</html><?php /**PATH C:\Users\Acer\Hydration-and-break-reminder-system\resources\views/home.blade.php ENDPATH**/ ?>