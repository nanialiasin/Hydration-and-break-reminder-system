<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/password-reset.css'); ?>
</head>
<body>
    <div class="phone-shell">
        <div class="header">
            <a href="<?php echo e(route('login')); ?>" class="back-link" aria-label="Back to login">←</a>
        </div>

        <section class="content">
            <div class="form-container">
                <h1>New Password</h1>
                <p class="subtitle">Please enter your new password</p>

                <form method="POST" action="<?php echo e(route('password.reset')); ?>" class="form">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <input
                            type="password"
                            name="password"
                            placeholder="Enter new password"
                            class="form-input"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirm new password"
                            class="form-input"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-continue">Continue</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
<?php /**PATH C:\Users\CHC PC\Herd\25s2-g15\resources\views/reset-password.blade.php ENDPATH**/ ?>