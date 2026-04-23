<section id="services" class="ui-landing-section bg-white dark:bg-neutral-950">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.95fr,1.05fr] lg:items-end">
            <div class="ui-reveal space-y-4">
                <p class="ui-landing-kicker">Our services</p>
                <h2 class="ui-landing-title">Creative systems built for impact</h2>
                <p class="ui-landing-body max-w-lg">
                    Kami menggabungkan strategi konten, produksi visual, dan workflow operasional agar tim bisa meluncurkan campaign lebih konsisten.
                </p>
            </div>
            <p class="ui-reveal max-w-xl text-sm leading-relaxed text-gray-500 dark:text-neutral-400" data-reveal-delay="1">
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
                <article class="ui-landing-card ui-reveal group min-w-[82%] p-7 md:min-w-0 dark:hover:border-indigo-400/35 dark:hover:shadow-indigo-900/20" data-reveal-delay="{{ $loop->index % 3 }}">
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
            <a href="#lead-capture" class="ui-btn-primary ui-reveal px-6 py-3">
                Let’s plan your next campaign
            </a>
        </div>
    </div>
</section>

