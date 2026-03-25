<footer class="border-t border-slate-200 bg-[#F8FAFC]">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-8">
            <div>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                        <img src="{{ asset('img/logo.png') }}" alt="Parthaistic Logo" class="h-6 w-6 object-contain" />
                    </div>
                    <div>
                        <p class="text-sm font-bold text-[#0B1220]">PT. Parthaistic Kreasi Mendunia</p>
                        <p class="text-xs text-[#6B7280]">Internal workflow & task management dashboard</p>
                    </div>
                </div>
                <p class="mt-4 text-xs text-[#6B7280] leading-relaxed">
                    © {{ date('Y') }} PT. Parthaistic Kreasi Mendunia. All rights reserved.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold tracking-[0.18em] uppercase text-[#6B7280]">Explore</p>
                    <ul class="mt-3 space-y-2">
                        <li><a class="text-sm text-[#4B5563] hover:text-[#0652FD] transition-colors" href="#features">Fitur</a></li>
                        <li><a class="text-sm text-[#4B5563] hover:text-[#0652FD] transition-colors" href="#how-it-works">Cara kerja</a></li>
                        <li><a class="text-sm text-[#4B5563] hover:text-[#0652FD] transition-colors" href="#faq">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <p class="text-xs font-semibold tracking-[0.18em] uppercase text-[#6B7280]">Account</p>
                    <ul class="mt-3 space-y-2">
                        @if (Route::has('login'))
                            <li><a class="text-sm text-[#4B5563] hover:text-[#0652FD] transition-colors" href="{{ route('login') }}">Login dashboard</a></li>
                        @endif
                        @if (auth()->check())
                            <li><a class="text-sm text-[#4B5563] hover:text-[#0652FD] transition-colors" href="{{ url('/dashboard') }}">Dashboard</a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

