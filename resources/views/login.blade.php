<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>TaskHub</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    </head>
    <body id="login">
        <img src="{{ asset('images/TaskHub.png') }}" alt="Logo" class="logo">
        
        <div class="sign-in-with">Sign In</div>

        <a href="{{ url('/auth/google') }}" class="continue-with-google">
            <img src="{{ asset('images/logo-google.png') }}" alt="Logo Google">
            <div class="text-wrapper">Sign In with Google</div>
        </a>

        <p class="don-t-have-an">
            <span class="text-wrapper">Don’t have an account? </span> <span class="span">Sign Up</span>
        </p>
    </body>
</html>
