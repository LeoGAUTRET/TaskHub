<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    </head>
    <body class="antialiased">
        @if(isset($username))
            <h1>Bienvenue, {{ $username }} !</h1>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                console.log('Demande de géolocalisation home blade');
                clem();

                // Votre autre code ici...
            });
            // requestGeolocation();
            // console.log('Demande de géolocalisation en coursssssssss...');
            // requestGeolocation();
            // // Demande la géolocalisation lorsque la page est chargée
            // window.addEventListener('load', function () {
            //     window.requestGeolocation();
            // });
        </script>
    </body>
</html>
