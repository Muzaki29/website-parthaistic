<section id="faq" class="bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <x-landing.section-heading
            kicker="FAQ"
            title="Pertanyaan umum seputar dashboard"
            subtitle="Jawaban singkat yang membantu tim memahami cara kerja Parthaistic Dashboard."
        />

        <div class="mt-12 grid lg:grid-cols-[0.9fr,1.1fr] gap-6 items-start">
            <div class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0652FD]/60 via-[#0EA5E9]/50 to-[#0652FD]/60 opacity-70"></div>
                <h3 class="text-lg font-bold text-[#0B1220]">Butuh jawaban cepat?</h3>
                <p class="mt-2 text-sm text-[#4B5563] leading-relaxed">
                    Jika Anda ingin menyesuaikan alur produksi untuk kebutuhan tim, Anda bisa mulai dari login dan melihat
                    contoh workspace produksi yang tersedia di dashboard.
                </p>
                <div class="mt-5 flex flex-col gap-3">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-[#0652FD] text-white text-sm font-semibold hover:bg-[#0546c4] transition-colors focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25">
                            Masuk untuk mulai
                        </a>
                    @endif
                    <a href="#features"
                       class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-slate-200 bg-white text-[#0B1220] text-sm font-semibold hover:bg-slate-50 transition-colors">
                        Lihat fitur utama
                    </a>
                </div>
            </div>

            <div class="space-y-3">
                @foreach ([
                    [
                        'q' => 'Bagaimana dashboard ini dipakai sehari-hari?',
                        'a' => 'Tim menggunakan dashboard untuk menyatukan data dari Trello, laporan manual, dan catatan produksi ke dalam satu tampilan yang rapi.'
                    ],
                    [
                        'q' => 'Siapa saja yang bisa mengakses dashboard ini?',
                        'a' => 'Hanya tim internal Parthaistic yang mendapatkan akun resmi. Akses dibatasi berdasarkan peran (admin, manager, employee).'
                    ],
                    [
                        'q' => 'Apakah klien bisa melihat progres konten?',
                        'a' => 'Ringkasan progres bisa diunduh dari dashboard lalu dibawa ke sesi presentasi atau dikirim sebagai laporan rutin ke klien.'
                    ],
                    [
                        'q' => 'Bagaimana dashboard terhubung dengan Trello & tools lain?',
                        'a' => 'Data diambil dari board Trello, disusun ulang agar mudah dibaca, lalu dipakai untuk mengukur performa dan status produksi di satu layar.'
                    ],
                    [
                        'q' => 'Apakah bisa upload file untuk setiap task?',
                        'a' => 'Ya. Setiap task dapat memiliki lampiran (attachments). Anda bisa upload file yang dibutuhkan produksi lalu mengelolanya dari detail task.'
                    ],
                    [
                        'q' => 'Ada fitur prioritas dan due date?',
                        'a' => 'Ada. Task memiliki priority (low, medium, high, urgent) dan due date. Tampilan menandai overdue dan due soon supaya tim fokus pada tenggat penting.'
                    ],
                ] as $item)
                    <details class="group rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <summary class="cursor-pointer list-none px-6 py-5 flex items-center justify-between gap-4">
                            <span class="text-sm font-semibold text-[#0B1220] pr-6">
                                {{ $item['q'] }}
                            </span>
                            <span aria-hidden="true" class="flex-shrink-0 h-9 w-9 rounded-xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center text-[#0652FD]">
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
                                </svg>
                            </span>
                        </summary>
                        <div class="px-6 pb-5 text-sm text-[#4B5563] leading-relaxed">
                            {{ $item['a'] }}
                        </div>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

