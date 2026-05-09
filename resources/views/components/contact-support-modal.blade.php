{{-- Requires Alpine on ancestor (e.g. body): showContactModal, contactSuccess --}}
<div>
    <div
        x-show="showContactModal"
        x-cloak
        class="fixed inset-0 z-[100] flex items-end justify-center overflow-y-auto px-4 pb-10 pt-4 sm:block sm:p-0"
        role="dialog"
        aria-modal="true"
        aria-labelledby="contact-support-title"
        @keydown.escape.window="showContactModal = false"
    >
        <div
            x-show="showContactModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm dark:bg-black/60"
            @click="showContactModal = false"
            aria-hidden="true"
        ></div>
        <span class="hidden sm:inline-block sm:h-screen sm:align-middle" aria-hidden="true">&#8203;</span>
        <div
            x-show="showContactModal"
            x-transition:enter="ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 sm:translate-y-0 sm:scale-95"
            class="relative z-[101] inline-block w-full max-w-lg transform overflow-hidden rounded-2xl border border-gray-200 bg-white text-left align-bottom shadow-2xl dark:border-gray-700 dark:bg-gray-800 sm:my-8 sm:align-middle"
            @click.stop
        >
            <div class="border-b border-gray-200 px-5 py-4 dark:border-gray-700">
                <div class="flex items-start gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary/20 dark:text-blue-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 id="contact-support-title" class="text-lg font-semibold text-gray-900 dark:text-white">Contact Support</h3>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ceritakan masalah Anda; tim IT akan menindaklanjuti.</p>
                    </div>
                    <button
                        type="button"
                        class="rounded-lg p-1.5 text-gray-500 transition hover:bg-gray-100 hover:text-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary dark:hover:bg-gray-700 dark:hover:text-gray-200"
                        @click="showContactModal = false"
                        aria-label="Tutup"
                    >
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="px-5 py-4">
                <div class="space-y-4">
                    <div>
                        <label for="contact-support-subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subjek</label>
                        <input id="contact-support-subject" type="text" class="ui-input mt-1 block w-full text-sm" placeholder="Ringkas masalah Anda">
                    </div>
                    <div>
                        <label for="contact-support-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pesan</label>
                        <textarea id="contact-support-message" rows="4" class="ui-input mt-1 block w-full text-sm" placeholder="Detail langkah yang sudah dicoba (opsional)"></textarea>
                    </div>
                </div>
            </div>
            <div class="flex flex-col-reverse gap-2 border-t border-gray-200 bg-gray-50 px-5 py-4 dark:border-gray-700 dark:bg-gray-900/40 sm:flex-row sm:justify-end">
                <button type="button" class="ui-btn-secondary px-4 py-2 text-sm" @click="showContactModal = false">
                    Batal
                </button>
                <button
                    type="button"
                    class="ui-btn-primary px-4 py-2 text-sm"
                    @click="showContactModal = false; contactSuccess = true; setTimeout(() => contactSuccess = false, 4000)"
                >
                    Kirim laporan
                </button>
            </div>
        </div>
    </div>

    <div
        x-show="contactSuccess"
        x-cloak
        x-transition
        class="fixed bottom-5 right-5 z-[110] w-full max-w-sm overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg ring-1 ring-black/5 dark:border-gray-700 dark:bg-gray-800"
        role="status"
    >
        <div class="flex items-start gap-3 p-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0 flex-1 pt-0.5">
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Terkirim</p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Laporan Anda telah dicatat. Tim IT akan menghubungi Anda jika diperlukan.</p>
            </div>
            <button type="button" class="shrink-0 rounded-md p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" @click="contactSuccess = false" aria-label="Tutup notifikasi">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
</div>
