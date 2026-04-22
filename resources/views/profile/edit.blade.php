<div class="space-y-6">
    <div>
        <h1 class="mb-2 text-4xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
        <p class="text-gray-600 dark:text-gray-400">Update your personal information and account settings</p>
    </div>

    @if (session()->has('success'))
        <div class="flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-300">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="ui-card max-w-4xl overflow-hidden p-8">
        <form wire:submit.prevent="updateProfile" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Photo Section -->
                <div class="col-span-1 flex flex-col items-center justify-center border-b border-gray-200 pb-6 dark:border-gray-700 md:border-b-0 md:border-r md:pb-0">
                    <div class="relative w-32 h-32 mb-4">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="h-full w-full rounded-full border-4 border-gray-200 object-cover shadow-sm dark:border-gray-700">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=0652FD&color=fff&size=128" class="h-full w-full rounded-full border-4 border-gray-200 object-cover shadow-sm dark:border-gray-700">
                        @endif
                    </div>
                    
                    <label class="block">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500 dark:text-gray-400
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-xs file:font-semibold
                          file:bg-blue-50 file:text-primary dark:file:bg-blue-900/30 dark:file:text-blue-200
                          hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50
                          cursor-pointer
                        "/>
                    </label>
                    @error('photo') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Form Section -->
                <div class="col-span-1 md:col-span-2 space-y-4">
                    <!-- Read-Only Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email Address (Read-only)</label>
                        <input type="text" value="{{ $email }}" disabled class="mt-1 block w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 p-2.5 text-gray-500 shadow-sm sm:text-sm dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Role (Read-only)</label>
                            <input type="text" value="{{ ucfirst($role) }}" disabled class="mt-1 block w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 p-2.5 text-gray-500 shadow-sm sm:text-sm dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                         <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Jabatan (Read-only)</label>
                            <input type="text" value="{{ $jabatan ?? '-' }}" disabled class="mt-1 block w-full cursor-not-allowed rounded-lg border border-gray-200 bg-gray-100 p-2.5 text-gray-500 shadow-sm sm:text-sm dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <!-- Editable Fields -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <input wire:model="name" type="text" id="name" class="ui-input mt-1 rounded-lg p-2.5 sm:text-sm">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password (Optional)</label>
                        <input wire:model="password" type="password" id="password" placeholder="Leave blank to keep current password" class="ui-input mt-1 rounded-lg p-2.5 sm:text-sm dark:placeholder-gray-400">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex justify-end border-t border-gray-200 pt-6 dark:border-gray-700">
                        <button type="submit" class="ui-btn-primary group relative flex items-center gap-2 overflow-hidden px-8 py-3">
                            <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span wire:loading.remove>Save Changes</span>
                            <span wire:loading>Saving...</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
