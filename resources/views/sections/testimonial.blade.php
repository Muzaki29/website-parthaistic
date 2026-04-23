<section id="testimonial" class="ui-landing-section bg-gradient-to-b from-white to-indigo-50/40 dark:from-neutral-950 dark:to-neutral-900/80">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="ui-reveal lg:col-span-1">
                <p class="ui-landing-kicker">Testimonials</p>
                <h2 class="ui-landing-title mt-2">What our clients say</h2>
            </div>

            @foreach ([
                ['quote' => 'Tim Parthaistic membantu kami menyederhanakan alur produksi konten yang sebelumnya berantakan.', 'name' => 'Sarah Thynier', 'role' => 'Brand Manager'],
                ['quote' => 'Kualitas visual naik, timeline lebih terjaga, dan komunikasi antar tim jadi jauh lebih efisien.', 'name' => 'Betozy Agency', 'role' => 'Creative Director'],
            ] as $item)
                <article class="ui-landing-card ui-reveal p-7" data-reveal-delay="{{ $loop->index }}">
                    <p class="ui-landing-body">“{{ $item['quote'] }}”</p>
                    <div class="mt-6">
                        <p class="text-sm font-semibold text-slate-900 dark:text-neutral-100">{{ $item['name'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-neutral-400">{{ $item['role'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

