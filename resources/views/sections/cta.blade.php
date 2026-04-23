<section id="lead-capture" class="ui-landing-section bg-white dark:bg-neutral-950">
    <div class="mx-auto w-full max-w-7xl px-6 lg:px-8">
        <div class="ui-reveal relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-900 via-indigo-900 to-blue-800 px-8 py-10 text-white shadow-2xl shadow-indigo-900/20 lg:px-10 lg:py-12">
            <div class="pointer-events-none absolute -right-20 -top-20 h-64 w-64 rounded-full bg-white/10 blur-3xl"></div>
            <div class="pointer-events-none absolute -left-20 -bottom-24 h-64 w-64 rounded-full bg-indigo-300/20 blur-3xl"></div>

            <div class="relative grid gap-10 lg:grid-cols-[1fr,0.95fr]">
                <div class="space-y-4">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-indigo-200">Create with us</p>
                    <h2 class="max-w-xl text-3xl font-semibold tracking-tight">Let’s build your next creative breakthrough</h2>
                    <p class="max-w-xl text-base text-indigo-100/90">
                        Isi form singkat di samping, tim kami akan menghubungi Anda untuk diskusi kebutuhan, scope, dan timeline project.
                    </p>
                    <div class="mt-6 flex flex-wrap items-center gap-2 text-xs font-medium text-indigo-100/90">
                        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1">No long proposal form</span>
                        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1">Respon awal <= 1x24 jam</span>
                        <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1">Creative team direct</span>
                    </div>
                </div>

                <div class="ui-reveal rounded-2xl border border-white/20 bg-white/10 p-6 backdrop-blur" data-reveal-delay="1">
                    @if (session('lead_success'))
                        <div class="mb-4 rounded-lg border border-emerald-300/30 bg-emerald-400/20 px-4 py-3 text-sm text-emerald-100">
                            {{ session('lead_success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-4 rounded-lg border border-rose-300/30 bg-rose-400/20 px-4 py-3 text-sm text-rose-100">
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('leads.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="form_rendered_at" value="{{ now()->timestamp }}">
                        <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                        <div>
                            <label for="name" class="mb-1.5 block text-sm font-medium text-indigo-100">Nama</label>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-lg border border-white/20 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none ring-indigo-400 transition focus:ring-2">
                        </div>
                        <div>
                            <label for="email" class="mb-1.5 block text-sm font-medium text-indigo-100">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-lg border border-white/20 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none ring-indigo-400 transition focus:ring-2">
                        </div>
                        <div>
                            <label for="company" class="mb-1.5 block text-sm font-medium text-indigo-100">Perusahaan / Brand</label>
                            <input id="company" name="company" type="text" value="{{ old('company') }}" class="w-full rounded-lg border border-white/20 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none ring-indigo-400 transition focus:ring-2">
                        </div>
                        <div>
                            <label for="project_brief" class="mb-1.5 block text-sm font-medium text-indigo-100">Project Brief</label>
                            <textarea id="project_brief" name="project_brief" rows="3" required class="w-full rounded-lg border border-white/20 bg-white/90 px-4 py-3 text-sm text-slate-900 outline-none ring-indigo-400 transition focus:ring-2">{{ old('project_brief') }}</textarea>
                        </div>

                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-lg bg-white px-6 py-3 text-sm font-semibold text-indigo-700 shadow-lg transition hover:bg-indigo-50">
                            Send inquiry
                        </button>
                        <p class="text-center text-xs text-indigo-100/80">Kami tidak akan membagikan data Anda ke pihak ketiga.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

