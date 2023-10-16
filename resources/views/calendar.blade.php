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
    <body>
        <nav class="menu">
            <div class="icon-home">
                <a href="/">
                    <?php echo file_get_contents(public_path('images/home.svg')); ?>
                </a>
            </div>

            <div class="icon-receipt">
                <a href="/agenda">
                    <?php echo file_get_contents(public_path('images/icon-receipt.svg')); ?>
                </a>
            </div>

            <div class="overlap">
                <div class="icon-history">
                    <?php echo file_get_contents(public_path('images/icon-history.svg')); ?>
                </div>
            </div>

            <div class="icon-notification">
                <div class="overlap-group">
                    <a href="/notifications">
                        <div class="img">
                            <?php echo file_get_contents(public_path('images/icon-notification.svg')); ?>
                        </div>
                        <div class="ellipse"></div>
                    </a>
                </div>
            </div>

            <div class="icon-profil">
                <a href="/profil">
                    <?php echo file_get_contents(public_path('images/user.svg')); ?>
                </a>
            </div>
        </nav>

        @if(isset($events))
            @foreach($events as $event)
                <p>{{ $event->title }}</p><br>
            @endforeach
        @endif
    </body>
</html>
