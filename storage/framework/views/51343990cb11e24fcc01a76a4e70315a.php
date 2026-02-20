<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password</title>
    <?php echo app('Illuminate\Foundation\Vite')('resources/css/password-reset.css'); ?>
</head>
<body>
    <div class="phone-shell">
        <div class="header">
            <a href="<?php echo e(route('login')); ?>" class="back-link" aria-label="Back to login">←</a>
        </div>

        <section class="content">
            <div class="form-container">
                <h1>Forgot Password</h1>
                <p class="subtitle">Enter your email address</p>

                <form method="POST" action="<?php echo e(route('password.email')); ?>" class="form">
                    <?php echo csrf_field(); ?>

                    <div class="form-group">
                        <input
                            type="email"
                            name="email"
                            placeholder="Email"
                            class="form-input"
                            required
                            autofocus
                        >
                    </div>

                    <button type="submit" class="btn btn-continue">Continue</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
<?php /**PATH C:\Users\CHC PC\Herd\25s2-g15\resources\views/forgot-password.blade.php ENDPATH**/ ?>