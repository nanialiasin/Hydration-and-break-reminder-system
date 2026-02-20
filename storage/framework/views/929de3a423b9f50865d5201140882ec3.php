<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign Up</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/register.css'); ?>
</head>
<body>
    <main class="phone-shell" role="main" aria-label="Sign up form">
        <div class="status-bar" aria-hidden="true">
            <span class="time">9:41</span>
        </div>

        <section class="content">
            <a href="<?php echo e(route('login')); ?>" class="back-link" aria-label="Back to sign in">←</a>

            <h1>Create Account</h1>
            <p class="lead">Fill your information below.</p>

            <form method="post" action="#">
                <?php echo csrf_field(); ?>
                <input class="field" type="text" name="name" placeholder="Name" autocomplete="name">
                <input class="field" type="email" name="email" placeholder="Email" autocomplete="email">
                <input class="field" type="password" name="password" placeholder="Password" autocomplete="new-password">
                <input class="field" type="password" name="password_confirmation" placeholder="Confirm Password" autocomplete="new-password">

                <p class="role-label">Select Role :</p>
                <div class="roles">
                    <label><input type="radio" name="role" value="coach"> Coach</label>
                    <label><input type="radio" name="role" value="athlete" checked> Athlete</label>
                </div>

                <button class="submit" type="submit">Sign Up</button>
            </form>

            <p class="signin">Already have account? <a href="<?php echo e(route('login')); ?>">Sign In</a></p>
            <p class="terms">By signing up, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></p>
        </section>
    </main>
</body>
</html>
<?php /**PATH C:\Users\CHC PC\Herd\25s2-g15\resources\views/register.blade.php ENDPATH**/ ?>