<section id="final-cta" class="bg-[#F1F5FF] relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_top_left,rgba(6,82,253,0.10)_0,transparent_55%)]"></div>
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <div class="relative overflow-hidden rounded-2xl border border-slate-200/70 bg-gradient-to-br from-white via-[#F1F5FF] to-[#F8FAFC] shadow-lg shadow-slate-900/10 p-6 lg:p-8">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(6,82,253,0.16)_0,transparent_55%)] pointer-events-none"></div>
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.12)_0,transparent_50%)] pointer-events-none"></div>
            <div class="grid lg:grid-cols-[1.1fr,0.9fr] gap-8 items-center">
                <div>
                    <p class="text-xs font-semibold tracking-[0.22em] uppercase text-[#0EA5E9]">Ready to streamline production?</p>
                    <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-[#0B1220]">
                        Masuk ke workspace tim Parthaistic
                        <span class="text-[#0652FD]">sekarang</span>.
                    </h2>
                    <p class="mt-4 text-sm sm:text-base text-[#4B5563] leading-relaxed max-w-2xl">
                        Mulai kelola task, atur prioritas & due date, upload attachments, dan pantau progres produksi dalam satu dashboard terpadu.
                    </p>
                </div>

                <div class="flex flex-col sm:items-end gap-3">
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gradient-to-r from-[#0652FD] to-[#0EA5E9] text-white text-sm font-semibold hover:from-[#0546c4] hover:to-[#0284C7] transition-all hover:shadow-lg focus:outline-none focus:ring-4 focus:ring-[#0652FD]/25 w-full sm:w-auto shadow-lg shadow-[#0652FD]/15">
                            Masuk untuk mengelola tugas
                        </a>
                    @endif
                    <a href="#how-it-works"
                       class="inline-flex items-center justify-center px-6 py-3 rounded-lg border border-slate-200/80 bg-white/55 backdrop-blur-md text-sm font-medium text-[#0B1220] hover:bg-white transition-colors hover:shadow-md w-full sm:w-auto focus:outline-none focus:ring-4 focus:ring-[#0652FD]/10">
                        Kembali ke langkah kerja
                    </a>
                </div>
            </div>

            <div class="mt-8 grid sm:grid-cols-3 gap-4">
                @foreach ([
                    ['label' => 'Role-based access', 'desc' => 'Akses dibatasi sesuai peran.'],
                    ['label' => 'Trello sync', 'desc' => 'Sinkronisasi data task produksi.'],
                    ['label' => 'Reports & export', 'desc' => 'Ringkasan siap untuk stakeholder.'],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md p-6 shadow-sm shadow-slate-900/10 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                        <p class="text-sm font-bold text-[#0B1220]">{{ $item['label'] }}</p>
                        <p class="mt-1 text-xs text-[#6B7280]">{{ $item['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

