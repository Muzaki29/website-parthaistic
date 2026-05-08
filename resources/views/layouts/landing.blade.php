<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Parthaistic'))</title>
    <meta name="description" content="Parthaistic Creative Agency - content creation, video production, and collaborative storytelling.">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <meta name="theme-color" content="#312E81">
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
    <style>
        body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        [x-cloak]{display:none !important;}
    </style>
</head>
<body class="bg-white text-neutral-900 antialiased dark:bg-neutral-950 dark:text-neutral-100"
    x-data="{ darkMode: document.documentElement.classList.contains('dark'), scrolled: false, mobileMenuOpen: false }"
    x-init="scrolled = window.scrollY > 10; window.addEventListener('theme:changed', (event) => darkMode = event.detail.theme === 'dark')"
    @scroll.window.passive="scrolled = window.scrollY > 10"
>
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 rounded-lg bg-white px-4 py-2 shadow-md">Skip to content</a>
    <header :class="scrolled ? 'bg-white/85 dark:bg-neutral-900/80 backdrop-blur-md shadow-sm border-neutral-200 dark:border-white/10' : 'bg-transparent border-transparent'" class="fixed inset-x-0 top-0 z-50 border-b transition-all duration-300">
        <nav class="mx-auto flex h-16 w-full max-w-7xl items-center justify-between px-6 lg:px-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-100 to-sky-100 dark:border-indigo-400/40 dark:from-indigo-500/20 dark:to-sky-400/20">
                    <img src="{{ asset('img/logo.png') }}" alt="Parthaistic Logo" class="h-6 w-6 object-contain">
                </span>
                <span class="text-sm font-semibold tracking-wide text-slate-900 dark:text-neutral-100">Parthaistic Agency</span>
            </a>
            <div class="hidden items-center gap-6 text-sm font-medium text-slate-600 dark:text-neutral-300 lg:flex">
                <a href="#services" class="hover:text-indigo-600">Services</a>
                <a href="#portfolio" class="hover:text-indigo-600">Portfolio</a>
                <a href="#process" class="hover:text-indigo-600">Process</a>
                <a href="#testimonial" class="hover:text-indigo-600">Testimonials</a>
            </div>
            <div class="flex items-center gap-2">
                <button @click="darkMode = window.toggleTheme() === 'dark'" class="ui-btn-secondary h-10 w-10 rounded-lg p-0 text-slate-600 dark:text-neutral-200">
                    <span x-show="!darkMode">☀</span>
                    <span x-show="darkMode" x-cloak>☾</span>
                </button>
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="ui-btn-secondary h-10 w-10 rounded-lg p-0 text-slate-600 dark:text-neutral-200 lg:hidden"
                    aria-label="Toggle navigation menu"
                >
                    <svg x-show="!mobileMenuOpen" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="mobileMenuOpen" x-cloak class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <a href="#lead-capture" class="ui-btn-primary rounded-lg px-4 py-2">
                    Start Project
                </a>
            </div>
        </nav>
        <div
            x-show="mobileMenuOpen"
            x-transition
            x-cloak
            @click.outside="mobileMenuOpen = false"
            class="border-t border-neutral-200 bg-white/95 px-6 py-4 backdrop-blur-md dark:border-white/10 dark:bg-neutral-900/95 lg:hidden"
        >
            <div class="flex flex-col gap-3 text-sm font-medium text-slate-700 dark:text-neutral-200">
                <a @click="mobileMenuOpen = false" href="#services" class="rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-white/10">Services</a>
                <a @click="mobileMenuOpen = false" href="#portfolio" class="rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-white/10">Portfolio</a>
                <a @click="mobileMenuOpen = false" href="#process" class="rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-white/10">Process</a>
                <a @click="mobileMenuOpen = false" href="#testimonial" class="rounded-lg px-3 py-2 hover:bg-slate-100 dark:hover:bg-white/10">Testimonials</a>
            </div>
        </div>
    </header>

    <main id="main-content" class="pt-16">
        @yield('content')
    </main>
    <x-scroll-to-top />
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const revealTargets = document.querySelectorAll('.ui-reveal');
            if (revealTargets.length) {
                const observer = new IntersectionObserver((entries, obs) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting) return;
                        entry.target.classList.add('is-visible');
                        obs.unobserve(entry.target);
                    });
                }, { threshold: 0.14, rootMargin: '0px 0px -8% 0px' });

                revealTargets.forEach((el) => observer.observe(el));
            }

            const countTargets = document.querySelectorAll('[data-countup-target]');
            countTargets.forEach((el) => {
                const target = Number(el.getAttribute('data-countup-target') || 0);
                const suffix = el.getAttribute('data-countup-suffix') || '';
                const duration = Number(el.getAttribute('data-countup-duration') || 1100);
                const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

                if (prefersReducedMotion || !target) {
                    el.textContent = `${target}${suffix}`;
                    return;
                }

                let started = false;
                const io = new IntersectionObserver((entries, obs) => {
                    entries.forEach((entry) => {
                        if (!entry.isIntersecting || started) return;
                        started = true;
                        const start = performance.now();
                        const tick = (now) => {
                            const progress = Math.min((now - start) / duration, 1);
                            const eased = 1 - Math.pow(1 - progress, 3);
                            const value = Math.round(target * eased);
                            el.textContent = `${value}${suffix}`;
                            if (progress < 1) requestAnimationFrame(tick);
                        };
                        requestAnimationFrame(tick);
                        obs.unobserve(entry.target);
                    });
                }, { threshold: 0.4 });
                io.observe(el);
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

