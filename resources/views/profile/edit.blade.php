<div class="space-y-6">
    <div>
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Edit Profile</h1>
        <p class="text-gray-600">Update your personal information and account settings</p>
    </div>

    @if (session()->has('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 overflow-hidden p-8 max-w-4xl">
        <form wire:submit.prevent="updateProfile" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Photo Section -->
                <div class="col-span-1 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-100 pb-6 md:pb-0">
                    <div class="relative w-32 h-32 mb-4">
                        @if ($photo)
                            <img src="{{ $photo->temporaryUrl() }}" class="w-full h-full rounded-full object-cover border-4 border-gray-100 shadow-sm">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=0652FD&color=fff&size=128" class="w-full h-full rounded-full object-cover border-4 border-gray-100 shadow-sm">
                        @endif
                    </div>
                    
                    <label class="block">
                        <span class="sr-only">Choose profile photo</span>
                        <input type="file" wire:model="photo" class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-full file:border-0
                          file:text-xs file:font-semibold
                          file:bg-blue-50 file:text-primary
                          hover:file:bg-blue-100
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
                            <label class="block text-sm font-medium text-gray-500">Email Address (Read-only)</label>
                            <input type="text" value="{{ $email }}" disabled class="mt-1 block w-full bg-gray-100 border-gray-200 rounded-md shadow-sm sm:text-sm text-gray-500 cursor-not-allowed border p-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Role (Read-only)</label>
                            <input type="text" value="{{ ucfirst($role) }}" disabled class="mt-1 block w-full bg-gray-100 border-gray-200 rounded-md shadow-sm sm:text-sm text-gray-500 cursor-not-allowed border p-2">
                        </div>
                         <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-500">Jabatan (Read-only)</label>
                            <input type="text" value="{{ $jabatan ?? '-' }}" disabled class="mt-1 block w-full bg-gray-100 border-gray-200 rounded-md shadow-sm sm:text-sm text-gray-500 cursor-not-allowed border p-2">
                        </div>
                    </div>

                    <hr class="border-gray-100 my-4">

                    <!-- Editable Fields -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input wire:model="name" type="text" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm border p-2">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                        <input wire:model="password" type="password" id="password" placeholder="Leave blank to keep current password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm border p-2">
                        @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-6 flex justify-end border-t border-gray-100">
                        <button type="submit" class="group relative overflow-hidden bg-gradient-to-r from-primary to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
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
