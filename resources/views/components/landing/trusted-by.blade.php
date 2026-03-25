<section id="trusted-by" class="bg-[#F8FAFC] relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_top_right,rgba(6,82,253,0.08)_0,transparent_55%)]"></div>
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">
            <div>
                <x-landing.section-heading
                    kicker="Dipercaya"
                    title="Dipakai untuk menjaga ritme produksi konten"
                    subtitle="Angka dan struktur trust ini bisa Anda sesuaikan saat punya logo klien asli."
                />
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-xl w-full">
                <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/10">
                    <p class="text-3xl font-bold text-[#0B1220]">10+</p>
                    <p class="mt-1 text-sm font-semibold text-[#0B1220]">Kategori layanan</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-lg shadow-slate-900/5">
                    <p class="text-3xl font-bold text-[#0B1220]">100+</p>
                    <p class="mt-1 text-sm font-semibold text-[#0B1220]">Project terselesaikan</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-lg shadow-slate-900/5">
                    <p class="text-3xl font-bold text-[#0B1220]">4</p>
                    <p class="mt-1 text-sm font-semibold text-[#0B1220]">Segmen utama</p>
                </div>
                <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-lg shadow-slate-900/5">
                    <p class="text-3xl font-bold text-[#0B1220]">∞</p>
                    <p class="mt-1 text-sm font-semibold text-[#0B1220]">Ide & cerita</p>
                </div>
            </div>
        </div>

            <div class="mt-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach (['Brand & Corporate', 'Content Creators', 'Education & Community', 'Produksi Internal'] as $label)
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-5 shadow-sm shadow-slate-900/10 hover:-translate-y-1 hover:shadow-md transition-all duration-300 flex items-center justify-center text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="h-10 w-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center">
                                <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l-1 1m11-10l-1 1M7 6h10M7 6l-2 2m2-2l2-2m-2 2l2 2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10l-3 3 2 2 3-3-2-2z"/>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-[#0B1220]">{{ $label }}</p>
                            <p class="text-[10px] text-[#6B7280]">Client logo</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 rounded-2xl border border-[#0652FD]/15 bg-[#0652FD]/5 p-5 shadow-sm shadow-slate-900/10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-[#0652FD]/10 border border-[#0652FD]/20 flex items-center justify-center">
                        <svg class="h-5 w-5 text-[#0652FD]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-1 14H6L5 7"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#0B1220]">Bersih, rapi, dan mudah diaudit</p>
                        <p class="text-xs text-[#6B7280] mt-1">Semua alur kerja tersimpan di satu tempat untuk tim dan stakeholder.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border border-slate-200 bg-white text-[#0B1220]">A–Z support</span>
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border border-slate-200 bg-white text-[#0B1220]">Workflow terpusat</span>
                </div>
            </div>
        </div>
    </div>
</section>

