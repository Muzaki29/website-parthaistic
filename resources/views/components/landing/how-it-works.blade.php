<section id="how-it-works" class="bg-[#F8FAFC]">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <x-landing.section-heading
            kicker="How it works"
            title="Langkah sederhana dari brief sampai rilis"
            subtitle="Didesain supaya tim produksi bisa bergerak cepat, terukur, dan tidak kehilangan konteks."
        />

        <div class="mt-12 grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ([
                [
                    'step' => '01',
                    'title' => 'Sync data & siapkan workflow',
                    'desc' => 'Ambil struktur status dari Trello dan sinkronkan data kartu tugas ke dashboard.',
                ],
                [
                    'step' => '02',
                    'title' => 'Atur prioritas, due date, dan status',
                    'desc' => 'Gunakan filter, search, serta status update untuk menjaga alur produksi tetap lancar.',
                ],
                [
                    'step' => '03',
                    'title' => 'Kerjakan dengan konteks lengkap',
                    'desc' => 'Lampiran file dan catatan produksi tersimpan langsung pada detail task.',
                ],
                [
                    'step' => '04',
                    'title' => 'Pantau progres & export laporan',
                    'desc' => 'Lihat analytics, ringkasan produksi, dan unduh laporan untuk kebutuhan stakeholder.',
                ],
            ] as $item)
                <article class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0EA5E9]/50 via-[#0652FD]/50 to-[#0EA5E9]/50 opacity-60"></div>
                    <div class="flex items-start justify-between gap-4">
                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-2xl bg-[#0652FD]/10 text-[#0652FD] border border-[#0652FD]/20 font-bold">
                            {{ $item['step'] }}
                        </span>
                        <div class="h-2.5 w-2.5 rounded-full bg-emerald-500/40 mt-3"></div>
                    </div>
                    <h3 class="mt-4 text-lg font-bold text-[#0B1220]">{{ $item['title'] }}</h3>
                    <p class="mt-2 text-sm text-[#4B5563] leading-relaxed">{{ $item['desc'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

