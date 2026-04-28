<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Login</title>
    @vite('resources/css/login.css')
</head>
<body>
    <main class="shell" role="main" aria-label="Login form">
        <div class="logo-wrap">
            <img
                src="{{ asset('images/hydrapulse-logo.svg') }}"
                alt="Hydrapulse logo"
                onerror="this.style.display='none';"
            >
        </div>

        <h1>Login</h1>
        <p class="subtitle">Welcome Back!</p>
        <p class="subtitle">Ready For Training?</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            @error('email')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            <input class="field" id="email" type="email" name="email" placeholder="Email" autocomplete="email" value="{{ old('email') }}">
            <input class="field" id="password" type="password" name="password" placeholder="Password" autocomplete="current-password">

            <a class="forgot" href="{{ route('password.request') }}">Forgot Password?</a>
            <button class="submit" type="submit">Sign In</button>
        </form>

        <p class="signup">
            Don't have account? <a href="{{ route('register') }}">Sign Up</a>
        </p>
    </main>
</body>
</html>
