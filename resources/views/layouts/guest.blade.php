<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <link rel="icon" href="/img/puck.ico">
    </head>
    <body>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
        <footer>
            Полезные ссылки: <br>
            Scorpion : <a target="_blank" href='https://th.sportscorpion.com/rus/tournament/'>https://th.sportscorpion.com/rus/tournament/ </a><br>
            Федерация: <a target="_blank" href='http://www.board-hockey.ru/'>http://www.board-hockey.ru</a>
        </footer>
    </body>
</html>
