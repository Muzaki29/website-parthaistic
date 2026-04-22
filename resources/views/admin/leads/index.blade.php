<x-layouts.app :title="'Lead Inbox'">
    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Lead Inbox</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pipeline singkat: tindak lanjuti lead dari landing tanpa CRM yang berlebihan.</p>
            </div>

            <form action="{{ route('admin.leads.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input
                    type="text"
                    name="q"
                    value="{{ $q }}"
                    placeholder="Cari nama/email/perusahaan..."
                    class="w-full sm:w-72 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm focus:border-primary focus:outline-none focus:ring-2 focus:ring-primary/20 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                >
                <select name="status" class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100">
                    <option value="">Semua status</option>
                    @foreach (\App\Models\Lead::statuses() as $st)
                        <option value="{{ $st }}" @selected($status === $st)>{{ str_replace('_', ' ', $st) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-lg bg-primary px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 transition-colors">
                    Terapkan
                </button>
            </form>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Kembali ke Dashboard
            </a>
            <a href="{{ route('landing') }}#lead-capture" class="inline-flex items-center rounded-lg border border-gray-300 px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                Lihat Form Landing
            </a>
        </div>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Perusahaan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Brief</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Assignee</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Aksi cepat</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($leads as $lead)
                            <tr class="align-top hover:bg-gray-50 dark:hover:bg-gray-900/20">
                                <td class="px-4 py-3 text-sm">
                                    @php
                                        $badge = match ($lead->status) {
                                            'new' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/40 dark:text-sky-200',
                                            'contacted' => 'bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-100',
                                            'qualified' => 'bg-indigo-100 text-indigo-900 dark:bg-indigo-900/30 dark:text-indigo-100',
                                            'closed_won' => 'bg-emerald-100 text-emerald-900 dark:bg-emerald-900/30 dark:text-emerald-100',
                                            'closed_lost' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100',
                                            'spam' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
                                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold capitalize {{ $badge }}">{{ str_replace('_', ' ', $lead->status) }}</span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">{{ $lead->last_activity_at?->format('d M Y H:i') ?? $lead->created_at?->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 text-sm font-semibold text-gray-800 dark:text-gray-100">{{ $lead->name }}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                    <a class="hover:underline" href="mailto:{{ $lead->email }}">{{ $lead->email }}</a>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">{{ $lead->company ?? '-' }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 max-w-xs">
                                    <div class="line-clamp-3">{{ $lead->project_brief }}</div>
                                    @if ($lead->notes)
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400"><span class="font-semibold">Catatan:</span> {{ \Illuminate\Support\Str::limit($lead->notes, 160) }}</p>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $lead->assignee?->name ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <div class="min-w-[180px] flex flex-col gap-2">
                                        <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="contacted">
                                            <button type="submit" class="w-full rounded-md border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">Hubungi</button>
                                        </form>
                                        <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="qualified">
                                            <button type="submit" class="w-full rounded-md border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">Qualified</button>
                                        </form>
                                        <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="closed_won">
                                            <button type="submit" class="w-full rounded-md border border-emerald-300 px-2 py-1 text-xs font-semibold text-emerald-900 hover:bg-emerald-50 dark:border-emerald-700 dark:text-emerald-100 dark:hover:bg-emerald-900/30">Menang</button>
                                        </form>
                                        <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="closed_lost">
                                            <button type="submit" class="w-full rounded-md border border-gray-300 px-2 py-1 text-xs font-semibold text-gray-800 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-100 dark:hover:bg-gray-700">Kalah</button>
                                        </form>
                                        <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="inline" onsubmit="return confirm('Tandai sebagai spam?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="spam">
                                            <button type="submit" class="w-full rounded-md border border-red-200 px-2 py-1 text-xs font-semibold text-red-700 hover:bg-red-50 dark:border-red-800 dark:text-red-200 dark:hover:bg-red-900/30">Spam</button>
                                        </form>
                                        <details class="rounded-md border border-dashed border-gray-300 p-2 text-xs dark:border-gray-600">
                                            <summary class="cursor-pointer font-semibold text-gray-700 dark:text-gray-200">Detail &amp; catatan</summary>
                                            <form action="{{ route('admin.leads.update', $lead) }}" method="POST" class="mt-2 space-y-2">
                                                @csrf
                                                @method('PATCH')
                                                <label class="block text-gray-600 dark:text-gray-300">Status</label>
                                                <select name="status" class="w-full rounded border border-gray-300 bg-white px-2 py-1 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                                    @foreach (\App\Models\Lead::statuses() as $st)
                                                        <option value="{{ $st }}" @selected($lead->status === $st)>{{ str_replace('_', ' ', $st) }}</option>
                                                    @endforeach
                                                </select>
                                                <label class="block text-gray-600 dark:text-gray-300">Assign</label>
                                                <select name="assigned_to" class="w-full rounded border border-gray-300 bg-white px-2 py-1 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                                                    <option value="">—</option>
                                                    @foreach ($assignees as $u)
                                                        <option value="{{ $u->id }}" @selected((int) $lead->assigned_to === (int) $u->id)>{{ $u->name }} ({{ $u->role }})</option>
                                                    @endforeach
                                                </select>
                                                <label class="block text-gray-600 dark:text-gray-300">Catatan internal</label>
                                                <textarea name="notes" rows="3" class="w-full rounded border border-gray-300 bg-white px-2 py-1 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('notes', $lead->notes) }}</textarea>
                                                <button type="submit" class="w-full rounded-md bg-primary px-2 py-1.5 font-semibold text-white hover:bg-blue-700">Simpan</button>
                                            </form>
                                        </details>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500 dark:text-gray-400">Belum ada lead masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>
            {{ $leads->links() }}
        </div>
    </div>
</x-layouts.app>
