<section id="testimonials" class="bg-[#F8FAFC]">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <x-landing.section-heading
            kicker="Testimoni"
            title="Dari tim yang menjalankan produksi"
            subtitle="Contoh kutipan realistis untuk memberi gambaran—akan diganti dengan testimoni asli ketika tersedia."
        />

        <div class="mt-12 grid md:grid-cols-3 gap-6">
            @foreach ([
                [
                    'name' => 'Producer',
                    'role' => 'Parthaistic Team',
                    'quote' => 'Dengan Trello sync, status kartu langsung masuk ke dashboard. Brief, task, dan revisi tetap nyambung sampai rilis.'
                ],
                [
                    'name' => 'Editor',
                    'role' => 'Creative Production',
                    'quote' => 'Priority dan due date bikin saya cepat menentukan revisi mana yang harus dikejar lebih dulu saat tenggat semakin dekat.'
                ],
                [
                    'name' => 'Project Lead',
                    'role' => 'Brand Project',
                    'quote' => 'Reports yang bisa di-filter dan export membuat komunikasi progres ke stakeholder lebih rapi dan mudah dipahami.'
                ],
            ] as $t)
                <article class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0652FD]/60 via-[#0EA5E9]/50 to-[#0652FD]/60 opacity-70"></div>
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="h-11 w-11 rounded-2xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                                <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
                                    <circle cx="12" cy="7" r="4" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-[#0B1220]">{{ $t['name'] }}</p>
                                <p class="text-xs text-[#6B7280]">{{ $t['role'] }}</p>
                            </div>
                        </div>
                        <span class="text-[11px] font-semibold text-[#0652FD] bg-[#0652FD]/10 border border-[#0652FD]/20 rounded-full px-3 py-1">Verified</span>
                    </div>

                    <p class="mt-4 text-sm text-[#4B5563] leading-relaxed">
                        “{{ $t['quote'] }}”
                    </p>
                </article>
            @endforeach
        </div>
    </div>
</section>

