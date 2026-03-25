<section id="use-cases" class="bg-[#F8FAFC]">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <x-landing.section-heading
            kicker="Use cases"
            title="Solusi untuk berbagai kebutuhan produksi konten"
            subtitle="Beberapa cara tim bisa memakai dashboard ini untuk kerja yang lebih terstruktur."
        />

        <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                [
                    'title' => 'Brand & corporate',
                    'desc' => 'Kelola kampanye, company profile, konten edukasi, dan dokumentasi event dengan workflow yang rapi.',
                    'bullets' => ['Brief & revisi terhubung', 'Status produksi transparan'],
                ],
                [
                    'title' => 'Creator & channel',
                    'desc' => 'Atur seri konten, repurposing, dan jadwal produksi untuk YouTube, TikTok, dan Reels.',
                    'bullets' => ['Task per episode/format', 'Prioritas untuk pacing'],
                ],
                [
                    'title' => 'Education & community',
                    'desc' => 'Produksi e-learning, video pembelajaran, dan workshop dengan kontrol progres yang jelas.',
                    'bullets' => ['Lampiran materi per task', 'Export laporan untuk kebutuhan internal'],
                ],
                [
                    'title' => 'Tim internal Parthaistic',
                    'desc' => 'Dari ide sampai rilis, semua tercatat dalam satu sistem untuk kolaborasi tim yang lebih cepat.',
                    'bullets' => ['Dashboard terpusat', 'Bulk actions untuk update status cepat'],
                ],
            ] as $case)
                <article class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0EA5E9]/50 via-[#0652FD]/50 to-[#0EA5E9]/50 opacity-60"></div>
                    <div class="flex items-start justify-between gap-4">
                        <h3 class="text-lg font-bold text-[#0B1220]">{{ $case['title'] }}</h3>
                        <div class="h-10 w-10 rounded-2xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                            <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                            </svg>
                        </div>
                    </div>
                    <p class="mt-3 text-sm text-[#4B5563] leading-relaxed">{{ $case['desc'] }}</p>
                    <ul class="mt-4 space-y-2">
                        @foreach ($case['bullets'] as $b)
                            <li class="flex items-start gap-2">
                                <span class="mt-1 h-2 w-2 rounded-full bg-emerald-500/60"></span>
                                <span class="text-sm text-[#4B5563]">{{ $b }}</span>
                            </li>
                        @endforeach
                    </ul>
                </article>
            @endforeach
        </div>
    </div>
</section>

