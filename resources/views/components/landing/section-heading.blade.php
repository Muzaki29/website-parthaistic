@props([
    'kicker' => null,
    'title' => null,
    'subtitle' => null,
    'align' => 'left',
])

<div class="{{ $align === 'center' ? 'text-center mx-auto' : '' }} max-w-3xl">
    @if(!empty($kicker))
        <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-[0.22em] uppercase text-[#0EA5E9]">
            <span aria-hidden="true" class="h-2 w-2 rounded-full bg-[#0EA5E9] shadow-[0_0_0_4px_rgba(14,165,233,0.12)]"></span>
            <span>{{ $kicker }}</span>
        </p>
    @endif

    @if(!empty($title))
        <h2 class="mt-3 text-3xl sm:text-4xl font-semibold tracking-tight text-[#0B1220] leading-tight">
            {{ $title }}
        </h2>
    @endif

    @if(!empty($subtitle))
        <p class="mt-4 text-sm sm:text-base text-[#4B5563] leading-relaxed max-w-3xl">
            {{ $subtitle }}
        </p>
    @endif
</div>

