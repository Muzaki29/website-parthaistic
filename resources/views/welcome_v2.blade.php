<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Parthaistic') }}</title>
    <meta name="description" content="Parthaistic Workflow & Task Management Dashboard untuk mengelola ide, produksi, dan rilis dalam satu tampilan terpadu.">
    <meta name="theme-color" content="#0652FD">

    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/logo.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
        </style>
    @endif

    <meta property="og:title" content="{{ config('app.name', 'Parthaistic') }}">
    <meta property="og:description" content="Kelola workflow produksi konten Parthaistic dengan task, priority, due date, dan export reports.">
    <meta property="og:type" content="website">
    <meta property="og:image" content="{{ asset('img/logo.png') }}">
    <link rel="canonical" href="{{ url('/') }}">

    <script defer src="https://unpkg.com/alpinejs@3.14.2/dist/cdn.min.js"></script>
    <style>[x-cloak]{display:none !important;}</style>
</head>

<body class="bg-[#FDFDFC] text-[#0B1220] min-h-screen">
    <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 bg-white border border-slate-200 rounded-xl px-4 py-2 shadow-sm">
        Skip to content
    </a>

    <div
        class="min-h-screen flex flex-col"
        x-data="{ scrolled: false }"
        x-init="scrolled = window.scrollY > 10"
        @scroll.window.passive="scrolled = window.scrollY > 10"
    >
        <header
            :class="scrolled ? 'bg-white/80 backdrop-blur-md shadow-sm border-slate-200/50' : 'bg-white/0 backdrop-blur-0 shadow-none border-transparent'"
            class="fixed top-0 left-0 w-full z-50 h-16 border-b border-transparent transition-all duration-300"
            role="banner"
        >
            <div class="max-w-6xl mx-auto px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <a href="{{ url('/') }}" class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-center">
                            <img src="{{ asset('img/logo.png') }}" alt="Parthaistic Logo" class="h-7 w-7 object-contain">
                        </div>
                        <div class="leading-tight">
                            <p class="text-sm font-bold text-[#0B1220]">Parthaistic</p>
                            <p class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Workflow Dashboard</p>
                        </div>
                    </a>
                </div>

                <nav class="flex items-center gap-3">
                    <a href="https://parthaistic.com"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-full border border-slate-200 text-sm font-semibold text-[#4B5563] hover:text-[#0B1220] hover:bg-slate-50 transition-colors">
                        Website utama <span aria-hidden="true">↗</span>
                    </a>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex items-center px-4 py-2 rounded-full bg-[#0B1220] text-white text-sm font-semibold hover:bg-black transition-colors focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25">
                                Masuk ke dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center px-4 py-2 rounded-full bg-[#0B1220] text-white text-sm font-semibold hover:bg-black transition-colors focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25">
                                Login dashboard
                            </a>
                        @endauth
                    @endif
                </nav>
            </div>
        </header>

        <main id="content" class="flex-1 pt-16">
            <x-landing.hero />
            <x-landing.trusted-by />
            <x-landing.key-benefits />
            <x-landing.how-it-works />
            <x-landing.features />
            <x-landing.use-cases />
            <x-landing.value />
            <x-landing.testimonials />
            <x-landing.faq />
            <x-landing.final-cta />
        </main>

        <x-scroll-to-top />

        <x-landing.footer />
    </div>
</body>
</html>

