<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/login.css'); ?>
</head>
<body>
    <main class="shell" role="main" aria-label="Login form">
        <div class="logo-wrap">
            <img
                src="<?php echo e(asset('images/hydrapulse-logo.svg')); ?>"
                alt="Hydrapulse logo"
                onerror="this.style.display='none';"
            >
        </div>

        <h1>Login</h1>
        <p class="subtitle">Welcome Back Coach!</p>
        <p class="subtitle">Ready For Training?</p>

        <form method="get" action="<?php echo e(route('home')); ?>">
            <?php echo csrf_field(); ?>
            <input class="field" id="email" type="email" name="email" placeholder="Email" autocomplete="email">
            <input class="field" id="password" type="password" name="password" placeholder="Password" autocomplete="current-password">

            <a class="forgot" href="<?php echo e(route('password.request')); ?>">Forgot Password?</a>
            <button class="submit" type="submit">Sign In</button>
        </form>

        <p class="signup">
            Don't have account? <a href="<?php echo e(route('register')); ?>">Sign Up</a>
        </p>
    </main>
</body>
</html>
<?php /**PATH C:\Users\CHC PC\Herd\25s2-g15\resources\views/login.blade.php ENDPATH**/ ?>