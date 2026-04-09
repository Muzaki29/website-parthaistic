<section id="services" class="bg-white py-20 lg:py-24 dark:bg-neutral-950">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.95fr,1.05fr] lg:items-end">
            <div class="space-y-4">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">Our services</p>
                <h2 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-neutral-100">Creative systems built for impact</h2>
                <p class="max-w-lg text-base text-gray-600 dark:text-neutral-300">
                    Kami menggabungkan strategi konten, produksi visual, dan workflow operasional agar tim bisa meluncurkan campaign lebih konsisten.
                </p>
            </div>
            <p class="max-w-xl text-sm leading-relaxed text-gray-500 dark:text-neutral-400">
                Fokus kami bukan hanya output visual, tapi juga kualitas proses: briefing jelas, eksekusi cepat, dan handoff yang minim revisi berulang.
            </p>
        </div>

        <div class="mt-12 -mx-6 overflow-x-auto px-6 pb-2 md:mx-0 md:px-0">
            <div class="flex gap-5 md:grid md:grid-cols-2">
            @foreach ([
                ['title' => 'Brand Visual Production', 'desc' => 'Photo, video, dan visual campaign untuk meningkatkan konsistensi brand touchpoint.'],
                ['title' => 'Content Operations', 'desc' => 'Membangun alur kerja konten dari planning hingga publish agar tim tidak kehilangan konteks.'],
                ['title' => 'Social Content Acceleration', 'desc' => 'Produksi format cepat untuk Reels, Shorts, TikTok, dan kanal distribusi digital lainnya.'],
                ['title' => 'Creative Strategy Workshop', 'desc' => 'Sesi kolaborasi strategis untuk menyelaraskan message, visual language, dan objective campaign.'],
            ] as $service)
                <article class="group min-w-[82%] md:min-w-0 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-lg dark:border-white/10 dark:bg-neutral-900 dark:hover:border-indigo-400/35 dark:hover:shadow-indigo-900/20">
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-neutral-100">{{ $service['title'] }}</h3>
                    <p class="mt-3 text-base text-gray-600 dark:text-neutral-300">{{ $service['desc'] }}</p>
                    <div class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 dark:text-indigo-300">
                        Learn more
                        <span aria-hidden="true" class="transition group-hover:translate-x-1">→</span>
                    </div>
                </article>
            @endforeach
            </div>
        </div>
        <div class="mt-8 flex justify-center md:mt-10">
            <a href="#lead-capture" class="inline-flex items-center rounded-lg bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition duration-300 ease-out hover:scale-105 hover:from-indigo-700 hover:to-blue-600">
                Let’s plan your next campaign
            </a>
        </div>
    </div>
</section>

