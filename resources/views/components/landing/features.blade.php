<section id="features" class="bg-[#F1F5FF]">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <x-landing.section-heading
            kicker="Features"
            title="Fitur penting untuk produksi yang terstruktur"
            subtitle="Mulai dari Trello sync sampai export laporan, semuanya dirancang untuk memudahkan koordinasi dan eksekusi."
        />

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                [
                    'title' => 'Trello sync',
                    'desc' => 'Sinkronisasi data kartu menjadi task yang mudah dipantau.',
                    'accent' => 'bg-[#0652FD]/10 text-[#0652FD]',
                    'icon' => 'sync',
                ],
                [
                    'title' => 'Dashboard analytics',
                    'desc' => 'Ringkasan status, tren produksi, dan aktivitas 30 hari.',
                    'accent' => 'bg-emerald-500/10 text-emerald-700',
                    'icon' => 'chart',
                ],
                [
                    'title' => 'Reports: filter & search',
                    'desc' => 'Cari task berdasarkan judul/deskripsi + filter status, user, dan priority.',
                    'accent' => 'bg-[#0652FD]/10 text-[#0652FD]',
                    'icon' => 'search',
                ],
                [
                    'title' => 'Pagination & bulk actions',
                    'desc' => 'Update status massal dan hapus task sesuai kebutuhan tim.',
                    'accent' => 'bg-amber-500/10 text-amber-700',
                    'icon' => 'check',
                ],
                [
                    'title' => 'Task detail editor',
                    'desc' => 'Edit judul, deskripsi, notes, status, priority, dan due date.',
                    'accent' => 'bg-sky-500/10 text-sky-700',
                    'icon' => 'edit',
                ],
                [
                    'title' => 'Priority & due date',
                    'desc' => 'Low/Medium/High/Urgent + indikator overdue / due soon untuk fokus eksekusi.',
                    'accent' => 'bg-rose-500/10 text-rose-700',
                    'icon' => 'clock',
                ],
                [
                    'title' => 'Attachments',
                    'desc' => 'Upload file per task dan kelola lampiran dari detail task.',
                    'accent' => 'bg-emerald-500/10 text-emerald-700',
                    'icon' => 'file',
                ],
                [
                    'title' => 'Role-based access',
                    'desc' => 'Akses dibatasi berdasarkan peran (admin/manager/employee).',
                    'accent' => 'bg-[#0652FD]/10 text-[#0652FD]',
                    'icon' => 'shield',
                ],
            ] as $feature)
                <article class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0652FD]/60 via-[#0EA5E9]/50 to-[#0652FD]/60 opacity-70"></div>
                    <div class="flex items-start gap-4">
                        <div class="relative h-11 w-11 rounded-2xl border border-slate-200/70 {{ $feature['accent'] }} flex items-center justify-center">
                            @if ($feature['icon'] === 'sync')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m18 0l-4 4m4-4l-4-4"/>
                                </svg>
                            @elseif ($feature['icon'] === 'chart')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3v18h18"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 14l4-4 4 2 2-6"/>
                                </svg>
                            @elseif ($feature['icon'] === 'search')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.3-4.3"/>
                                </svg>
                            @elseif ($feature['icon'] === 'check')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 6L9 17l-5-5"/>
                                </svg>
                            @elseif ($feature['icon'] === 'edit')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4 12.5-12.5z"/>
                                </svg>
                            @elseif ($feature['icon'] === 'clock')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <circle cx="12" cy="12" r="9"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7v5l3 2"/>
                                </svg>
                            @elseif ($feature['icon'] === 'file')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                </svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                </svg>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-[#0B1220]">{{ $feature['title'] }}</h3>
                            <p class="mt-2 text-sm text-[#4B5563] leading-relaxed">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

