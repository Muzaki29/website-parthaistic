<section id="value" class="bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <x-landing.section-heading
            kicker="Value"
            title="Operasional lebih rapi, keputusan lebih cepat"
            subtitle="Tanpa mengubah cara kerja tim Anda—dashboard ini membantu menyatukan konteks agar eksekusi lebih terukur."
        />

        <div class="mt-12 grid lg:grid-cols-[0.95fr,1.05fr] gap-6 items-start">
            <div class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0652FD]/60 via-[#0EA5E9]/50 to-[#0652FD]/60 opacity-70"></div>
                <h3 class="text-lg font-bold text-[#0B1220]">Snapshot workflow</h3>
                <p class="mt-2 text-sm text-[#4B5563]">
                    Preview ringkasan workflow produksi (angka placeholder untuk landing page).
                </p>

                <dl class="mt-5 grid sm:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200 bg-[#F8FAFC] p-4">
                        <dt class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Task aktif</dt>
                        <dd class="mt-1 text-2xl font-bold text-[#0B1220]">32</dd>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-[#F8FAFC] p-4">
                        <dt class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Project berjalan</dt>
                        <dd class="mt-1 text-2xl font-bold text-[#0B1220]">7</dd>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-[#F8FAFC] p-4">
                        <dt class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Tim terlibat</dt>
                        <dd class="mt-1 text-2xl font-bold text-[#0B1220]">4 squad</dd>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-[#F8FAFC] p-4">
                        <dt class="text-[11px] uppercase tracking-[0.18em] text-[#6B7280]">Channel konten</dt>
                        <dd class="mt-1 text-2xl font-bold text-[#0B1220]">5+</dd>
                    </div>
                </dl>

                @if (Route::has('login'))
                    <div class="mt-6">
                        <a href="{{ route('login') }}"
                           class="inline-flex w-full sm:w-auto items-center justify-center px-6 py-3 rounded-lg bg-[#0652FD] text-white text-sm font-semibold hover:bg-[#0546c4] transition-colors shadow-lg shadow-[#0652FD]/20 focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25">
                            Masuk untuk melihat workspace tim
                        </a>
                    </div>
                @endif
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                @foreach ([
                    [
                        'title' => 'Koordinasi tanpa kehilangan konteks',
                        'desc' => 'Brief, task, revisi, dan lampiran file ada di satu tempat.',
                        'icon' => 'context',
                    ],
                    [
                        'title' => 'Fokus pada pekerjaan yang paling penting',
                        'desc' => 'Prioritas dan indikator due date membantu tim bertindak lebih cepat.',
                        'icon' => 'focus',
                    ],
                    [
                        'title' => 'Audit & reporting lebih mudah',
                        'desc' => 'Laporan performa bisa dibagikan ke klien atau stakeholder secara terstruktur.',
                        'icon' => 'report',
                    ],
                    [
                        'title' => 'Responsif untuk kebutuhan tim yang berubah',
                        'desc' => 'Filter, search, dan pagination mempermudah pencarian data saat produksi padat.',
                        'icon' => 'speed',
                    ],
                ] as $item)
                    <article class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                        <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0EA5E9]/50 via-[#0652FD]/50 to-[#0EA5E9]/50 opacity-60"></div>
                        <div class="flex items-start gap-4">
                            <div class="h-11 w-11 rounded-2xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                                @if ($item['icon'] === 'context')
                                    <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.121 2.121 0 013 3L8 18l-4 1 1-4 11.5-11.5z"/>
                                    </svg>
                                @elseif ($item['icon'] === 'focus')
                                    <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @elseif ($item['icon'] === 'report')
                                    <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4v4H7z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 13h4v4h-4z"/>
                                    </svg>
                                @else
                                    <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-[#0B1220]">{{ $item['title'] }}</h4>
                                <p class="mt-2 text-sm text-[#4B5563] leading-relaxed">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

