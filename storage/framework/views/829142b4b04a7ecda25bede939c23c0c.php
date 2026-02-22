<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'Hydrapulse')); ?></title>

    <style>
        :root {
            color-scheme: light;
            font-family: "Inter", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            background: #ececec;
            padding: 24px;
        }

        .splash-shell {
            width: min(620px, 100%);
            aspect-ratio: 9 / 16;
            border-radius: 18px;
            background: #cfd2db;
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.18);
            overflow: hidden;
            position: relative;
        }

        .splash-center {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
        }

        .logo-ring {
            width: min(55vw, 360px);
            aspect-ratio: 1;
            border-radius: 999px;
            background: radial-gradient(circle at center, #ffffff 30%, #f4f7fb 70%);
            border: 10px solid #dde2ea;
            box-shadow: 0 0 0 4px rgba(188, 226, 245, 0.65) inset;
            display: grid;
            place-items: center;
            padding: 34px;
        }

        .logo-ring img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .fallback-mark {
            font-size: clamp(28px, 5.2vw, 44px);
            font-weight: 700;
            letter-spacing: 0.06em;
            color: #2298de;
            text-align: center;
            line-height: 1.1;
        }

        .subtext {
            position: absolute;
            bottom: 26px;
            left: 0;
            right: 0;
            text-align: center;
            color: #5f6677;
            font-size: 13px;
            letter-spacing: 0.03em;
        }

        @media (max-width: 640px) {
            body {
                padding: 8px;
            }

            .splash-shell {
                width: min(92vw, 430px);
            }

            .logo-ring {
                width: min(66vw, 290px);
                padding: 28px;
                border-width: 8px;
            }
        }
    </style>
</head>
<body>
    <?php
        $loginTarget = Route::has('login') ? route('login') : url('/login');
    ?>

    <main class="splash-shell" role="main" aria-label="Hydrapulse splash screen">
        <div class="splash-center">
            <div class="logo-ring">
                <img
                    src="<?php echo e(asset('images/hydrapulse-logo.svg')); ?>"
                    alt="Hydrapulse logo"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';"
                >
                <div class="fallback-mark" style="display:none;">hydra<br>pulse</div>
            </div>
        </div>

        <div class="subtext">Redirecting to login...</div>
    </main>

    <script>
        setTimeout(() => {
            window.location.href = <?php echo json_encode($loginTarget, 15, 512) ?>;
        }, 3000);
    </script>
</body>
</html>
<?php /**PATH C:\Users\Acer\Hydration-and-break-reminder-system\resources\views/welcome.blade.php ENDPATH**/ ?>