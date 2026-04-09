<div class="min-h-screen bg-gradient-to-br from-white via-indigo-50/40 to-blue-50/60 px-4 py-10 sm:px-6 dark:from-neutral-950 dark:via-neutral-900 dark:to-indigo-950/40">
    <div class="mx-auto w-full max-w-md">
        <div class="rounded-2xl border border-slate-200/80 bg-white/90 p-8 shadow-xl shadow-slate-900/5 backdrop-blur dark:border-white/10 dark:bg-neutral-900/85 dark:shadow-indigo-950/30">
            <div class="mb-6 text-center">
                <img src="{{ asset('img/logo.png') }}" alt="Parthaistic" class="mx-auto h-12 w-12 object-contain">
                <h1 class="mt-4 text-3xl font-semibold text-slate-900 dark:text-neutral-100">Welcome back, creator</h1>
                <p class="mt-2 text-base text-gray-600 dark:text-neutral-300">Masuk untuk melanjutkan workflow produksi tim.</p>
            </div>

            @if (session()->has('success'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-400/30 dark:bg-emerald-500/15 dark:text-emerald-300">
                    {{ session('success') }}
                </div>
            @endif

            <form wire:submit.prevent="login" class="space-y-4" x-data="{ showPassword: false }">
                <div>
                    <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-neutral-300">Email</label>
                    <input wire:model.defer="email" id="email" type="email" autofocus required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-white/15 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:ring-indigo-500/40">
                    @error('email') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-neutral-300">Password</label>
                    <div class="relative">
                        <input wire:model.defer="password" id="password" :type="showPassword ? 'text' : 'password'" required class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-11 text-sm text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-white/15 dark:bg-neutral-900 dark:text-neutral-100 dark:focus:ring-indigo-500/40">
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 px-3 text-xs font-semibold text-slate-500 hover:text-slate-700 dark:text-neutral-400 dark:hover:text-neutral-200">
                            <span x-text="showPassword ? 'Hide' : 'Show'"></span>
                        </button>
                    </div>
                    @error('password') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-blue-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/20 transition duration-300 ease-out hover:scale-[1.02] hover:from-indigo-700 hover:to-blue-600">
                    <span wire:loading.remove>Login</span>
                    <span wire:loading>Processing...</span>
                </button>
            </form>

            <p class="mt-5 text-center text-sm text-gray-600 dark:text-neutral-400">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Daftar sekarang</a>
            </p>
        </div>
    </div>
</div>
