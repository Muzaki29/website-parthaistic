<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trello Cards</title>
    <x-theme-script />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script>tailwind.config = { darkMode: 'class' };</script>
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="min-h-full bg-slate-50 text-slate-900 antialiased dark:bg-neutral-950 dark:text-neutral-100">
    <main class="mx-auto w-full max-w-5xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
        <div class="mb-6 flex items-center justify-between gap-3">
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Trello Board Cards</h1>
            <button
                onclick="window.toggleTheme()"
                class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-700 hover:bg-slate-50 dark:border-white/10 dark:bg-neutral-900 dark:text-neutral-200 dark:hover:bg-neutral-800"
            >
                Toggle theme
            </button>
        </div>

        <div class="space-y-4">
            @forelse($cards as $card)
                <article class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-neutral-900 sm:p-5">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-neutral-100">{{ $card['name'] }}</h2>
                    <p class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-neutral-300">{{ $card['description'] ?: 'No description' }}</p>
                    <div class="mt-3 grid gap-2 text-sm text-slate-500 dark:text-neutral-400 sm:grid-cols-2">
                        <p>List: <span class="font-medium text-slate-700 dark:text-neutral-200">{{ $card['list_name'] }}</span></p>
                        <p>Due: <span class="font-medium text-slate-700 dark:text-neutral-200">{{ $card['due'] ?? '-' }}</span></p>
                        <p class="sm:col-span-2">Labels: <span class="font-medium text-slate-700 dark:text-neutral-200">{{ implode(', ', $card['labels']) ?: '-' }}</span></p>
                    </div>
                    @if(!empty($card['url']))
                        <a href="{{ $card['url'] }}" target="_blank" rel="noopener" class="mt-4 inline-flex items-center text-sm font-semibold text-indigo-600 hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                            Open in Trello
                        </a>
                    @endif
                </article>
            @empty
                <div class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-600 dark:border-white/15 dark:bg-neutral-900 dark:text-neutral-300">
                    No cards available or Trello API is unreachable.
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
