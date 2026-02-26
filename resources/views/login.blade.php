<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

        <title>{{ config('app.name', 'ADECE') }}</title>
        <link rel="manifest" href="/manifest.json" />
        <meta name="theme-color" content="white">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="application-name" content="{{ config('app.name', 'ADECE') }}">
        <link rel="icon" href="/images/logos/logo.png">
        <meta name="msapplication-TileImage" content="/images/logos/logo.png">
        <meta name="msapplication-TileColor" content="#000">
        <!-- Apple -->
        <link rel="apple-touch-icon" href="/images/logos/logo.png">
        <link rel="apple-touch-icon" sizes="36x36" href="/images/logos/36.png">
        <link rel="apple-touch-icon" sizes="48x48" href="/images/logos/48.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/images/logos/72.png">
        <link rel="apple-touch-icon" sizes="96x96" href="/images/logos/96.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/images/logos/144.png">
        <link rel="apple-touch-icon" sizes="192x192" href="/images/logos/192.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="#0476F2">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'ADECE') }}">

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
    </head>
    <body class="font-inter antialiased bg-white text-gray-600 sidebar-expanded">
        <div id="app"></div>
        <script>
            // Cria um objeto global acessível pelo Vue
            window.laravel_flash = {
                success: @json(session('message')), // Pega do ->with('message', ...)
                error: @json($errors->first('message')) // Pega do ->withErrors(...)
            };
            console.log('longi.Laravel Flash Data Injected:', window.laravel_flash);
        </script>
        <!-- Scripts -->
        <script src="/js/app.js"></script>

        
    </body>
</html>