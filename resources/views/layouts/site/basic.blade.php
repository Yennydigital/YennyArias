<!DOCTYPE html>
<html data-bs-theme="dark" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('Umbra | Cartas que llegan a tu correo') }}</title>
    <meta name="description" content="{{ __('Umbra: una carta a la vez, sin ruido. Ideas y relatos para leer con calma.') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/favicon.png') . '?v=2' }}">

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
