<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>History</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/history.css'); ?>
</head>
<body>
    <main class="app-shell" role="main">
        <div class="content">
            <header class="page-header">
                <a href="<?php echo e(route('home')); ?>" class="back-btn" aria-label="Back to home">←</a>
                <h1 class="page-title">History</h1>
            </header>

            <div class="activities">
                <article class="activity-card">
                    <div class="activity-header">
                        <div class="activity-name">
                            <svg class="activity-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <path d="M8 12h8M12 8v8"></path>
                            </svg>
                            <span>Badminton</span>
                        </div>
                        <div class="activity-time">
                            <p class="date">Yesterday</p>
                            <p class="duration">1hr 15min</p>
                        </div>
                    </div>
                    <div class="hydration-section">
                        <span class="hydration-label">Hydration %</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%;"></div>
                        </div>
                    </div>
                </article>

                <article class="activity-card">
                    <div class="activity-header">
                        <div class="activity-name">
                            <svg class="activity-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path>
                            </svg>
                            <span>Jogging</span>
                        </div>
                        <div class="activity-time">
                            <p class="date">2 days ago</p>
                            <p class="duration">45 min</p>
                        </div>
                    </div>
                    <div class="hydration-section">
                        <span class="hydration-label">Hydration %</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 65%;"></div>
                        </div>
                    </div>
                </article>
            </div>
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
            <a href="<?php echo e(route('history')); ?>" class="nav-item active" aria-label="History">
                <img src="<?php echo e(asset('images/History Button.svg')); ?>" alt="History" width="24" height="24">
            </a>
            <a href="#" class="nav-item" aria-label="Profile">
                <img src="<?php echo e(asset('images/Account Button.svg')); ?>" alt="Account" width="24" height="24">
            </a>
        </nav>
    </main>
</body>
</html>
<?php /**PATH C:\Users\Acer\Hydration-and-break-reminder-system\resources\views/history.blade.php ENDPATH**/ ?>