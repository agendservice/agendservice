<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <title>{{ config('app.name', 'Facilivery') }}</title>

    <script>
        // Use document.documentElement para garantir que a classe 'dark' esteja no <html>
        const rootElement = document.documentElement;

        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            rootElement.classList.add('dark');
        } else if (localStorage.theme === 'light') {
             rootElement.classList.remove('dark');
        } 
        // Se a preferência não for definida ('theme' in localStorage não existir) e 
        // o sistema operacional estiver em modo escuro, o código acima já aplica 'dark'.
        // Não é necessário um 'else' para remover, a menos que localStorage.theme == 'light'.
    </script>

    <link rel="manifest" href="/manifest.json" />
    <meta name="theme-color" content="white">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="{{ config('app.name', 'Blank') }}">
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
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Blank') }}">

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
</head>
<body class="font-inter antialiased text-gray-600 dark:text-gray-400 sidebar-expanded">
    
    


<div id="app">
    <app-intranet ></app-intranet>
</div>

    <!-- Scripts -->
     <script>
        // Cria um objeto global acessível pelo Vue
        window.laravel_flash = {
            usuario: @json(auth()->user() ?? null),
            success: @json(session('message')),
            error: @json($errors->first('message')) 
        };
    </script>
    <script src="/js/app.js"></script>
    <!-- <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('sw.js');
            });
        }
    </script> -->

    
</body>
</html>