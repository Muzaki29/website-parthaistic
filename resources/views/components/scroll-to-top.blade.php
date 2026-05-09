@props([
    'threshold' => 300,
])

<div
    x-cloak
    x-data="{ show: false }"
    x-init="
        const set = () => {
            const container = $el.closest('[data-scroll-container]');
            const y = container ? container.scrollTop : window.scrollY;
            show = y > @js($threshold);
        };

        set();

        const container = $el.closest('[data-scroll-container]');
        const target = container || window;
        target.addEventListener('scroll', set, { passive: true });
        if ($el && Array.isArray($el._x_cleanups)) {
            $el._x_cleanups.push(() => target.removeEventListener('scroll', set));
        }
    "
    x-show="show"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 transform translate-y-2 scale-95"
    x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 transform translate-y-2 scale-95"
    class="fixed bottom-6 right-6 z-50"
>
    <button
        type="button"
        aria-label="Kembali ke atas"
        title="Kembali ke atas"
        @click="
            const container = $el.closest('[data-scroll-container]');
            if (container) {
                container.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        "
        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-white shadow-lg transition hover:scale-105 hover:bg-slate-800 focus:outline-none focus:ring-4 focus:ring-indigo-500/25 dark:bg-slate-100 dark:text-slate-900 dark:hover:bg-white"
    >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m-7 7 7-7 7 7" />
        </svg>
    </button>
</div>

