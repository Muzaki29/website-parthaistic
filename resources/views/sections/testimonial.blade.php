<section id="testimonial" class="bg-gradient-to-b from-white to-indigo-50/40 py-20 lg:py-24 dark:from-neutral-950 dark:to-neutral-900/80">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-1">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">Testimonials</p>
                <h2 class="mt-2 text-3xl font-semibold tracking-tight text-slate-900 dark:text-neutral-100">What our clients say</h2>
            </div>

            @foreach ([
                ['quote' => 'Tim Parthaistic membantu kami menyederhanakan alur produksi konten yang sebelumnya berantakan.', 'name' => 'Sarah Thynier', 'role' => 'Brand Manager'],
                ['quote' => 'Kualitas visual naik, timeline lebih terjaga, dan komunikasi antar tim jadi jauh lebih efisien.', 'name' => 'Betozy Agency', 'role' => 'Creative Director'],
            ] as $item)
                <article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm dark:border-white/10 dark:bg-neutral-900">
                    <p class="text-base leading-relaxed text-gray-600 dark:text-neutral-300">“{{ $item['quote'] }}”</p>
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $item['role'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

