<section id="process" class="bg-white py-20 lg:py-24 dark:bg-neutral-950">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[1fr,1fr]">
            <div class="space-y-4">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">Our process</p>
                <h2 class="text-3xl font-semibold tracking-tight text-slate-900 dark:text-neutral-100">Simple workflow, premium output</h2>
                <p class="max-w-lg text-base text-gray-600 dark:text-neutral-300">
                    Kami menjaga alur tetap ringan untuk klien, namun ketat di sisi kualitas eksekusi tim produksi.
                </p>
            </div>

            <div class="space-y-3 md:hidden">
                @foreach ([
                    ['step' => '01', 'title' => 'Discovery Brief', 'desc' => 'Kami petakan objective, audience, tone, dan kebutuhan output visual.'],
                    ['step' => '02', 'title' => 'Creative Direction', 'desc' => 'Konsep visual dibangun dengan struktur pesan yang jelas dan mudah dieksekusi.'],
                    ['step' => '03', 'title' => 'Production Sprint', 'desc' => 'Asset diproduksi dalam sprint terarah, dengan feedback loop yang singkat.'],
                    ['step' => '04', 'title' => 'Launch & Iteration', 'desc' => 'Konten dirilis, dipantau, lalu diiterasi berdasarkan performa dan insight.'],
                ] as $item)
                    <details class="rounded-2xl border border-slate-200 bg-slate-50/70 p-5 dark:border-white/10 dark:bg-neutral-900/70">
                        <summary class="flex cursor-pointer list-none items-center justify-between gap-3">
                            <span class="text-base font-semibold text-slate-900 dark:text-neutral-100">{{ $item['title'] }}</span>
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-xs font-semibold text-white">{{ $item['step'] }}</span>
                        </summary>
                        <p class="mt-3 text-base text-gray-600 dark:text-neutral-300">{{ $item['desc'] }}</p>
                    </details>
                @endforeach
            </div>

            <div class="hidden space-y-4 md:block">
                @foreach ([
                    ['step' => '01', 'title' => 'Discovery Brief', 'desc' => 'Kami petakan objective, audience, tone, dan kebutuhan output visual.'],
                    ['step' => '02', 'title' => 'Creative Direction', 'desc' => 'Konsep visual dibangun dengan struktur pesan yang jelas dan mudah dieksekusi.'],
                    ['step' => '03', 'title' => 'Production Sprint', 'desc' => 'Asset diproduksi dalam sprint terarah, dengan feedback loop yang singkat.'],
                    ['step' => '04', 'title' => 'Launch & Iteration', 'desc' => 'Konten dirilis, dipantau, lalu diiterasi berdasarkan performa dan insight.'],
                ] as $item)
                    <article class="rounded-2xl border border-slate-200 bg-slate-50/70 p-6 shadow-sm dark:border-white/10 dark:bg-neutral-900/70">
                        <div class="flex items-start gap-4">
                            <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-600 to-blue-500 text-sm font-semibold text-white shadow-md">
                                {{ $item['step'] }}
                            </span>
                            <div>
                                <h3 class="text-xl font-semibold text-slate-900 dark:text-neutral-100">{{ $item['title'] }}</h3>
                                <p class="mt-2 text-base text-gray-600 dark:text-neutral-300">{{ $item['desc'] }}</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>

