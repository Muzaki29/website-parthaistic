<section id="key-benefits" class="bg-white">
    <div class="max-w-6xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
        <div class="flex flex-col items-start">
            <x-landing.section-heading
                kicker="Keunggulan"
                title="Satu studio, banyak solusi—dikelola rapi dalam satu dashboard"
                subtitle="Dari perencanaan, produksi sampai distribusi konten digital untuk brand, institusi, hingga kreator personal."
            />
        </div>

        <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([
                [
                    'title' => 'Workflow studio terpusat',
                    'desc' => 'Semua brief, task, dan revisi terhubung ke satu sistem agar tidak tercerai.',
                    'icon' => 'workflow',
                    'accent' => 'bg-[#0652FD]/10 text-[#0652FD]',
                ],
                [
                    'title' => 'Monitoring kapasitas tim produksi',
                    'desc' => 'Pantau progres produksi dari level project sampai individu dengan jelas.',
                    'icon' => 'monitor',
                    'accent' => 'bg-emerald-500/10 text-emerald-700',
                ],
                [
                    'title' => 'Prioritas & due date yang actionable',
                    'desc' => 'Low, Medium, High, dan Urgent + indikator overdue/due soon untuk fokus eksekusi.',
                    'icon' => 'priority',
                    'accent' => 'bg-amber-500/10 text-amber-700',
                ],
                [
                    'title' => 'Attachments per task',
                    'desc' => 'Upload file yang dibutuhkan untuk produksi, lalu kelola daftar lampiran di detail task.',
                    'icon' => 'files',
                    'accent' => 'bg-sky-500/10 text-sky-700',
                ],
                [
                    'title' => 'Reports yang bisa di-filter',
                    'desc' => 'Cari berdasarkan judul/deskripsi, filter status, user, dan priority dengan pagination.',
                    'icon' => 'reports',
                    'accent' => 'bg-[#0652FD]/10 text-[#0652FD]',
                ],
                [
                    'title' => 'Bulk actions untuk cepat update status',
                    'desc' => 'Ubah status task dalam jumlah besar supaya alur review berjalan lebih efisien.',
                    'icon' => 'bulk',
                    'accent' => 'bg-emerald-500/10 text-emerald-700',
                ],
            ] as $benefit)
                <article class="relative rounded-2xl border border-slate-200/70 bg-white/70 backdrop-blur-md shadow-sm shadow-slate-900/10 p-7 hover:-translate-y-1 hover:shadow-md transition-all duration-300">
                    <div class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-[#0652FD]/60 via-[#0EA5E9]/50 to-[#0652FD]/60 opacity-70"></div>
                    <div class="flex items-start gap-4">
                        <div class="relative h-11 w-11 rounded-2xl {{ $benefit['accent'] }} flex items-center justify-center border border-slate-200/70">
                            @if ($benefit['icon'] === 'workflow')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                                </svg>
                            @elseif ($benefit['icon'] === 'monitor')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                </svg>
                            @elseif ($benefit['icon'] === 'priority')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-1 14H6L5 7"/>
                                </svg>
                            @elseif ($benefit['icon'] === 'files')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 2v6h6"/>
                                </svg>
                            @elseif ($benefit['icon'] === 'reports')
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h4v4H7z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 13h4v4h-4z"/>
                                </svg>
                            @else
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 20h10"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v14"/>
                                </svg>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-[#0B1220]">{{ $benefit['title'] }}</h3>
                            <p class="mt-2 text-sm text-[#4B5563] leading-relaxed">{{ $benefit['desc'] }}</p>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

