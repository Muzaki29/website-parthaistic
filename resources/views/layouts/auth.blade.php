<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Authentication - Parthaistic' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <x-theme-script />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script>
            tailwind.config = { darkMode: 'class' };
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script defer src="https://unpkg.com/alpinejs@3.14.2/dist/cdn.min.js"></script>
    <style>body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }</style>
    @livewireStyles
</head>
<body class="bg-neutral-50 text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-100">
    {{ $slot }}
    @livewireScripts
</body>
</html>

