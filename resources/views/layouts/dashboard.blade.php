<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard - Parthaistic' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <x-theme-script />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            primary: '#4F46E5',
                            surface: '#111827',
                        }
                    }
                }
            }
        </script>
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        [x-cloak] { display: none !important; }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @livewireStyles
</head>
<body
    class="bg-neutral-50 text-neutral-900 antialiased transition-colors duration-300 dark:bg-neutral-950 dark:text-neutral-100"
    x-data="{
        sidebarOpen: false,
        showContactModal: false,
        contactSuccess: false,
        darkMode: document.documentElement.classList.contains('dark'),
        init() {
            window.addEventListener('theme:changed', (event) => this.darkMode = event.detail.theme === 'dark');
        },
        toggleDarkMode() {
            this.darkMode = window.toggleTheme() === 'dark';
        }
    }"
>
    <div class="flex h-screen overflow-hidden">
        @include('layouts.sidebar')

        <div data-scroll-container class="relative flex flex-1 flex-col overflow-x-hidden overflow-y-auto bg-neutral-50 transition-colors duration-300 dark:bg-neutral-950">
            @include('layouts.navigation')

            <main class="w-full flex-grow p-4 md:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <x-scroll-to-top />
        </div>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealTargets = document.querySelectorAll('.ui-reveal, .ui-reveal-soft');
            if (!revealTargets.length) return;

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (prefersReducedMotion) {
                revealTargets.forEach((el) => el.classList.add('is-visible'));
                return;
            }

            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    obs.unobserve(entry.target);
                });
            }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

            revealTargets.forEach((el) => observer.observe(el));
        });
    </script>
    @stack('scripts')
</body>
</html>

