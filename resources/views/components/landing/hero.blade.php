<section aria-labelledby="hero-title" class="relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(6,82,253,0.12)_0,transparent_55%),radial-gradient(circle_at_bottom,rgba(251,146,60,0.07)_0,transparent_60%)]"></div>
    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(2,6,23,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(2,6,23,0.05)_1px,transparent_1px)] bg-[size:48px_48px] opacity-20 pointer-events-none"></div>

    <div class="relative max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <div class="grid lg:grid-cols-[1.15fr,0.85fr] gap-16 items-start">
            <div class="space-y-8">
                <div class="space-y-3">
                    <p class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-slate-200/70 bg-white/70 backdrop-blur-md text-[11px] font-semibold tracking-[0.22em] uppercase text-[#0652FD] shadow-sm">
                        <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#0652FD]/10 border border-[#0652FD]/15">
                            <svg class="h-3.5 w-3.5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            </svg>
                        </span>
                        Workflow Produksi Konten Terpusat
                    </p>

                    <h1 id="hero-title" class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.05] tracking-tight text-[#0B1220]">
                        Kelola ide, eksekusi, dan rilis
                        <span class="bg-gradient-to-r from-[#0652FD] to-[#0EA5E9] bg-clip-text text-transparent">dalam satu dashboard</span>.
                    </h1>

                    <p class="text-base sm:text-lg text-[#4B5563] max-w-xl leading-relaxed">
                        Parthaistic Dashboard menyatukan workflow produksi konten dalam satu tempat:
                        sinkronisasi Trello ke task, prioritas & due date yang actionable, lampiran per task,
                        hingga laporan terfilter yang siap dipakai untuk evaluasi dan stakeholder.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 sm:items-center">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gradient-to-r from-[#0652FD] to-[#0EA5E9] text-white text-sm sm:text-base font-semibold shadow-lg shadow-[#0652FD]/20 hover:from-[#0546c4] hover:to-[#0284C7] transition-all hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25"
                           aria-label="Masuk untuk mengelola tugas">
                            Masuk untuk mengelola tugas
                            <span aria-hidden="true" class="ml-2">→</span>
                        </a>
                    @endif

                    <a href="#how-it-works"
                       class="inline-flex items-center justify-center px-6 py-3 rounded-lg border border-slate-200/80 bg-white/55 backdrop-blur-md text-sm sm:text-base font-medium text-[#0B1220] hover:bg-white transition-colors hover:shadow-md focus:outline-none focus:ring-4 focus:ring-[#0652FD]/10 shadow-sm">
                        Lihat cara kerjanya
                    </a>
                </div>

                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="flex items-start gap-3 rounded-2xl bg-white/65 backdrop-blur-sm border border-slate-200/70 p-4 shadow-sm shadow-slate-900/5">
                        <div class="mt-0.5 h-9 w-9 rounded-xl bg-[#0652FD]/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0B1220]">Role-based access</p>
                            <p class="text-xs text-[#6B7280]">Akses dibatasi sesuai peran.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl bg-white/65 backdrop-blur-sm border border-slate-200/70 p-4 shadow-sm shadow-slate-900/5">
                        <div class="mt-0.5 h-9 w-9 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 17l4 4 4-4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7l-4-4-4 4"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0B1220]">Trello sync</p>
                            <p class="text-xs text-[#6B7280]">Sinkronisasi data tugas tim.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 rounded-2xl bg-white/65 backdrop-blur-sm border border-slate-200/70 p-4 shadow-sm shadow-slate-900/5">
                        <div class="mt-0.5 h-9 w-9 rounded-xl bg-amber-500/10 flex items-center justify-center">
                            <svg class="h-5 w-5 text-amber-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-1 14H6L5 7"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#0B1220]">Reports & export</p>
                            <p class="text-xs text-[#6B7280]">Unduh laporan yang rapi.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-1">
                    <div class="flex items-start gap-3">
                        <div class="mt-1 h-7 w-7 rounded-xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                            <svg class="h-4 w-4 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                            </svg>
                        </div>
                        <div class="space-y-1">
                            <p class="text-sm sm:text-base text-[#6B7280] leading-relaxed">
                                Fokus pada eksekusi: brief, task, revisi, dan lampiran tersimpan rapi—supaya tim tidak kehilangan konteks.
                            </p>
                            <p class="text-sm sm:text-base text-[#6B7280] leading-relaxed">
                                Prioritas & due date membantu tim menentukan revisi mana yang harus dikejar lebih dulu.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        <p class="text-2xl font-bold text-[#0B1220]">10+</p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#6B7280]">Kategori layanan</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        <p class="text-2xl font-bold text-[#0B1220]">50+</p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#6B7280]">Brand & klien</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        <p class="text-2xl font-bold text-[#0B1220]">4</p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#6B7280]">Segmen utama</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        <p class="text-2xl font-bold text-[#0B1220]">∞</p>
                        <p class="mt-1 text-[10px] uppercase tracking-[0.18em] text-[#6B7280]">Ide & cerita</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-[#6B7280] mb-2">Layanan konten</p>
                        <ul class="space-y-1.5 text-sm text-[#374151]">
                            <li>Video editor & videographer</li>
                            <li>Creative writer & script</li>
                            <li>Youtube & channel management</li>
                            <li>Short video (Reels, TikTok, Shorts)</li>
                        </ul>
                    </div>
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/5 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-300">
                        <p class="text-[11px] font-semibold tracking-[0.18em] uppercase text-[#6B7280] mb-2">Produksi & edukasi</p>
                        <ul class="space-y-1.5 text-sm text-[#374151]">
                            <li>Commercial & event video</li>
                            <li>e-Learning & short film</li>
                            <li>Workshop content creation</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -top-10 -left-8 h-40 w-40 rounded-full bg-[#0652FD]/10 blur-2xl"></div>
                <div class="absolute -bottom-16 -right-10 h-44 w-44 rounded-full bg-amber-500/10 blur-2xl"></div>

            <div class="relative rounded-2xl border border-slate-200/70 bg-white/60 backdrop-blur-md shadow-lg shadow-slate-900/10 ring-1 ring-[#0652FD]/15 overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(6,82,253,0.22)_0,transparent_55%)] pointer-events-none"></div>
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.18)_0,transparent_52%)] pointer-events-none"></div>
                    <div class="relative px-5 py-4 border-b border-slate-200/70 bg-gradient-to-r from-white to-[#F1F5FF]">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl bg-[#0652FD]/10 flex items-center justify-center border border-[#0652FD]/20">
                                    <img src="{{ asset('img/logo.png') }}" alt="Parthaistic" class="h-6 w-6 object-contain" />
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-[#0B1220]">Parthaistic Dashboard</p>
                                    <p class="text-xs text-[#6B7280]">Snapshot produksi konten</p>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-500/10 text-emerald-700 border border-emerald-200">
                                Live-ready
                            </span>
                        </div>
                        <div class="mt-3 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-[#0652FD]/10 text-[#0652FD] border border-[#0652FD]/20">
                                    Tasks
                                </span>
                                <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold bg-white/70 text-[#4B5563] border border-slate-200/70">
                                    Reports
                                </span>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-500/10 text-amber-700 border border-amber-200">
                                Due soon
                            </span>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-2 gap-5">
                        <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm p-4">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Task aktif</p>
                            <p class="mt-1 text-2xl font-bold text-[#0B1220]">32</p>
                            <p class="mt-2 text-xs text-[#6B7280]">Prioritas & due date terpantau</p>
                        </div>

                        <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm p-4">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Project berjalan</p>
                            <p class="mt-1 text-2xl font-bold text-[#0B1220]">7</p>
                            <p class="mt-2 text-xs text-[#6B7280]">Status update dengan bulk action</p>
                        </div>

                        <div class="col-span-2 rounded-2xl border border-slate-200 bg-gradient-to-r from-[#0652FD]/5 via-white to-[#0EA5E9]/5 p-4">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Ringkasan status</p>
                            <div class="mt-3 space-y-2">
                                <div class="flex items-center justify-between gap-3 text-sm">
                                    <span class="text-[#0B1220] font-semibold">To Do</span>
                                    <span class="text-[#6B7280]">12</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full w-[55%] bg-[#0652FD] rounded-full"></div>
                                </div>

                                <div class="flex items-center justify-between gap-3 text-sm">
                                    <span class="text-[#0B1220] font-semibold">Doing</span>
                                    <span class="text-[#6B7280]">8</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full w-[35%] bg-amber-500 rounded-full"></div>
                                </div>

                                <div class="flex items-center justify-between gap-3 text-sm">
                                    <span class="text-[#0B1220] font-semibold">Done</span>
                                    <span class="text-[#6B7280]">12</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                    <div class="h-full w-[60%] bg-emerald-600 rounded-full"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2 rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm p-4">
                            <p class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Lampiran file per task</p>
                            <div class="mt-3 flex items-center gap-3">
                                <div class="h-10 w-10 rounded-xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-[#0B1220] truncate">Lampiran produksi</p>
                                    <p class="text-xs text-[#6B7280]">Tersimpan langsung di task</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-5 py-4 border-t border-slate-200/70 bg-white/70 backdrop-blur-md">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-xs text-[#6B7280]">
                                Dibangun untuk tim internal: dari ide, eksekusi, sampai rilis.
                            </p>
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                   class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gradient-to-r from-[#0652FD] to-[#0EA5E9] text-white text-sm font-semibold hover:from-[#0546c4] hover:to-[#0284C7] transition-all hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25 shadow-lg shadow-[#0652FD]/15">
                                    Mulai sekarang
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <p class="mt-4 text-xs text-[#6B7280]">
                    Contoh tampilan workspace tim di dashboard.
                </p>
            </div>
        </div>
    </div>
</section>

