<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Tim & Karyawan</h1>
            <p class="text-gray-600">Kelola anggota tim dan akses mereka</p>
        </div>
        
        @if(auth()->check() && auth()->user()->role === 'admin')
        <button wire:click="openCreateModal" class="group relative overflow-hidden bg-linear-to-r from-primary to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-xl shadow-lg shadow-primary/20 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Add User</span>
        </button>
        @endif
    </div>

    @if (session()->has('success'))
        <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl" role="alert">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Table Card -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-linear-to-r from-gray-50 dark:from-gray-700 to-gray-100/50 dark:to-gray-700/50 transition-colors duration-300">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Team Member
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Email
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Status
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider transition-colors duration-300">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700 transition-colors duration-300">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-700/50 transition-colors duration-300">
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                <div class="shrink-0 w-12 h-12 relative">
                                    <img class="w-full h-full rounded-xl ring-2 ring-gray-100 dark:ring-gray-700 transition-all duration-300"
                                        src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0652FD&color=fff&size=128&bold=true"
                                        alt="" />
                                    <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800 transition-colors duration-300"></div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white transition-colors duration-300">
                                        {{ $user->name }}
                                    </p>
                                    @if($user->jabatan)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 transition-colors duration-300">{{ $user->jabatan }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <p class="text-gray-900 dark:text-gray-200 font-medium transition-colors duration-300">{{ $user->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            @php
                                $badgeConfig = match($user->role) {
                                    'admin' => ['bg' => 'bg-red-100 dark:bg-red-900/30', 'text' => 'text-red-800 dark:text-red-300', 'border' => 'border-red-200 dark:border-red-800'],
                                    'manager' => ['bg' => 'bg-blue-100 dark:bg-blue-900/30', 'text' => 'text-blue-800 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800'],
                                    'employee' => ['bg' => 'bg-green-100 dark:bg-green-900/30', 'text' => 'text-green-800 dark:text-green-300', 'border' => 'border-green-200 dark:border-green-800'],
                                    default => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-300', 'border' => 'border-gray-200 dark:border-gray-600'],
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} border {{ $badgeConfig['border'] }} transition-all duration-300">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-semibold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 transition-all duration-300">
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                {{ ucfirst($user->status_akun ?? 'Active') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <button wire:click="editRole({{ $user->id }})" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-primary dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <button wire:click="deleteUser({{ $user->id }})" onclick="return confirm('Are you sure you want to delete this user?') || event.stopImmediatePropagation()" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Modal -->
    @if($showCreateModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeCreateModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
                <div class="bg-linear-to-r from-primary to-blue-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white" id="modal-title">Add New User</h3>
                        <button wire:click="closeCreateModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-4 space-y-5">
                        <div>
                            <label for="newName" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                            <input wire:model="newName" type="text" id="newName" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 outline-none">
                            @error('newName') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="newJabatan" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan <span class="text-gray-400 font-normal">(Optional)</span></label>
                            <input wire:model="newJabatan" type="text" id="newJabatan" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 outline-none" placeholder="e.g. Video Editor, Creative Writer">
                            @error('newJabatan') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="newEmail" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input wire:model="newEmail" type="email" id="newEmail" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 outline-none">
                            @error('newEmail') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="newPassword" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <input wire:model="newPassword" type="password" id="newPassword" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 outline-none">
                            @error('newPassword') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="newRole" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                            <select wire:model="newRole" id="newRole" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 outline-none">
                                <option value="employee">Employee</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Admin</option>
                            </select>
                            @error('newRole') <p class="text-red-500 text-xs mt-2 flex items-center gap-1"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="createUser" type="button" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-lg px-6 py-3 bg-linear-to-r from-primary to-blue-600 text-base font-semibold text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Create User
                    </button>
                    <button wire:click="closeCreateModal" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-xl border-2 border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Edit Modal -->
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" aria-hidden="true" wire:click="closeModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-200">
                <div class="bg-linear-to-r from-primary to-blue-600 px-6 py-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-white" id="modal-title">Edit Role: {{ $editingUser->name }}</h3>
                        <button wire:click="closeModal" class="text-white/80 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="bg-white px-6 pt-6 pb-4 sm:p-6 sm:pb-4">
                    <div class="mt-4">
                        <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Select Role</label>
                        <select wire:model="role" id="role" class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-200 rounded-xl focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-200 outline-none">
                            <option value="employee">Employee</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                    <button wire:click="updateRole" type="button" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 rounded-xl border border-transparent shadow-lg px-6 py-3 bg-linear-to-r from-primary to-blue-600 text-base font-semibold text-white hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-primary/20 transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Save Changes
                    </button>
                    <button wire:click="closeModal" type="button" class="w-full sm:w-auto inline-flex justify-center rounded-xl border-2 border-gray-300 shadow-sm px-6 py-3 bg-white text-base font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-4 focus:ring-gray-200 transition-all duration-200">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
