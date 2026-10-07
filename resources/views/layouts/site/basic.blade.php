<!DOCTYPE html>
<html data-bs-theme="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Yenny Arias | Desarrolladora full-stack</title>
    <meta name="description" content="Portfolio de Yenny Arias, desarrolladora full-stack: APIs con Python y FastAPI, aplicaciones con Laravel y Livewire, y automatización con n8n.">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
   
    @vite([
        'resources/css/site.css',
        'resources/js/site.js'
    ])

    @yield('head')

    @livewireStyles

</head>

<body>

    @include('components.site.navbar')

    @yield('content')

    @include('components.site.footer')


    @livewireScripts
    

    @stack('scripts')

</body>

</html>
