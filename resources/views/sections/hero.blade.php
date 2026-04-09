<section class="relative overflow-hidden bg-gradient-to-br from-white via-indigo-50/40 to-sky-50/60 dark:from-neutral-950 dark:via-neutral-900 dark:to-indigo-950/40">
    <div class="pointer-events-none absolute -top-24 -right-24 h-72 w-72 rounded-full bg-indigo-500/20 blur-3xl dark:bg-indigo-500/25"></div>
    <div class="pointer-events-none absolute -bottom-28 -left-16 h-72 w-72 rounded-full bg-sky-400/20 blur-3xl dark:bg-sky-400/20"></div>

    <div class="mx-auto grid w-full max-w-7xl gap-12 px-6 py-20 lg:grid-cols-[1.08fr,0.92fr] lg:items-center lg:px-8 lg:py-24">
        <div class="space-y-8">
            <span class="inline-flex items-center rounded-full border border-indigo-200 bg-white/80 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-indigo-700 dark:border-indigo-400/30 dark:bg-neutral-900/80 dark:text-indigo-300">
                Creative digital production
            </span>

            <div class="space-y-4">
                <h1 class="max-w-2xl text-5xl font-bold leading-[1.05] tracking-tight text-slate-900 dark:text-neutral-100">
                    Bring bold ideas to life beautifully
                </h1>
                <p class="max-w-xl text-base text-gray-600 leading-relaxed dark:text-neutral-300">
                    Kami membantu brand dan tim internal mengubah brief menjadi karya visual berkualitas tinggi
                    dengan alur produksi yang lebih cepat, rapi, dan terukur.
                </p>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <a href="#lead-capture" class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition hover:from-indigo-700 hover:to-blue-600">
                    Start your project
                </a>
                <a href="#portfolio" class="inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:border-indigo-300 hover:text-indigo-700 dark:border-white/15 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:border-indigo-400/40 dark:hover:text-indigo-300">
                    View portfolio
                </a>
            </div>

            <div class="grid max-w-xl gap-3 sm:grid-cols-3">
                @foreach ([
                    ['value' => '720+', 'label' => 'Projects launched'],
                    ['value' => '50+', 'label' => 'Active clients'],
                    ['value' => '24/7', 'label' => 'Collaboration flow'],
                ] as $item)
                    <div class="rounded-2xl border border-slate-200/80 bg-white/80 p-4 shadow-sm shadow-slate-900/5 dark:border-white/10 dark:bg-neutral-900/80">
                        <p class="text-2xl font-semibold text-slate-900 dark:text-neutral-100">{{ $item['value'] }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.14em] text-gray-500 dark:text-neutral-400">{{ $item['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="relative">
            <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-indigo-500/15 to-sky-400/15 blur-2xl"></div>
            <div class="relative rounded-3xl border border-white/70 bg-white/80 p-4 shadow-2xl shadow-slate-900/10 backdrop-blur dark:border-white/10 dark:bg-neutral-900/70 dark:shadow-indigo-900/30">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 dark:border-white/10 dark:bg-neutral-900">
                    <div class="mb-5 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-slate-900 dark:text-neutral-100">Creative Workflow</p>
                            <p class="text-xs text-gray-500 dark:text-neutral-400">From brief to visual launch</p>
                        </div>
                        <span class="rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">
                            Live
                        </span>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-neutral-800/70">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-500 dark:text-neutral-400">Incoming briefs</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-neutral-100">38</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-neutral-800/70">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-500 dark:text-neutral-400">In production</p>
                            <p class="mt-2 text-2xl font-semibold text-slate-900 dark:text-neutral-100">14</p>
                        </div>
                        <div class="sm:col-span-2 rounded-xl border border-indigo-100 bg-gradient-to-r from-indigo-50 to-sky-50 p-4 dark:border-indigo-400/25 dark:from-indigo-500/10 dark:to-sky-400/10">
                            <p class="text-xs uppercase tracking-[0.16em] text-gray-500 dark:text-neutral-400">Pipeline health</p>
                            <div class="mt-3 space-y-2">
                                <div class="h-2 overflow-hidden rounded-full bg-white dark:bg-neutral-800">
                                    <div class="h-full w-[72%] rounded-full bg-gradient-to-r from-indigo-600 to-blue-500"></div>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-neutral-300">72% job flow on-track this week.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

