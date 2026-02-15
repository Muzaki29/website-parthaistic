<div class="min-h-screen flex flex-col md:flex-row bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    <!-- Left Side: Brand Showcase (Hidden on Mobile) -->
    <div class="hidden md:flex md:w-[55%] relative flex-col items-center justify-center overflow-hidden text-center p-12">
        <!-- Animated Gradient Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-blue-600 to-indigo-700"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-10"></div>
        
        <!-- Animated Blob Shapes -->
        <div class="absolute top-0 -left-20 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
        <div class="absolute top-0 -right-20 w-96 h-96 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-20 left-20 w-96 h-96 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        
        <div class="relative z-10 flex flex-col items-center max-w-lg">
            <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
                <img src="{{ asset('img/logo.png') }}" alt="Parthaistic Logo" class="h-28 w-auto drop-shadow-2xl">
            </div>
            
            <h1 class="text-5xl font-bold text-white mb-4 tracking-tight leading-tight">
                PT. Parthaistic<br>Kreasi Mendunia
            </h1>
            <p class="text-xl text-blue-100 font-semibold tracking-wider uppercase mb-2">Your Digital Creator</p>
            <p class="text-base text-blue-200 font-light mb-12 max-w-md">
                We create imaginative & innovative content to make people happy
            </p>
            
            <!-- Feature Highlights -->
            <div class="grid grid-cols-2 gap-4 w-full max-w-md mb-12">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-2xl font-bold text-white mb-1">10+</div>
                    <div class="text-xs text-blue-100 uppercase tracking-wide">Services</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/20">
                    <div class="text-2xl font-bold text-white mb-1">100+</div>
                    <div class="text-xs text-blue-100 uppercase tracking-wide">Projects</div>
                </div>
            </div>
            
            <div class="text-blue-100 text-sm opacity-90 mt-auto">
                <p>&copy; {{ date('Y') }} Parthaistic Digital Agency</p>
                <p class="text-xs mt-1">Depok, Jakarta Metropolitan Area</p>
            </div>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="w-full md:w-[45%] bg-white/80 backdrop-blur-sm flex items-center justify-center p-8 md:p-12 relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5" style="background-image: radial-gradient(circle, #0652FD 1px, transparent 1px); background-size: 20px 20px;"></div>
        
        <div class="w-full max-w-md relative z-10">
            <!-- Mobile Logo -->
            <div class="mb-8 text-center md:hidden">
                <img src="{{ asset('img/logo.png') }}" alt="Parthaistic Logo" class="h-16 mx-auto mb-4">
                <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
            </div>
            
            <!-- Desktop Header -->
            <div class="mb-10 hidden md:block">
                <h2 class="text-4xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                <p class="text-gray-600">Sign in to access your dashboard</p>
            </div>

            <form wire:submit.prevent="login" class="space-y-6 bg-white/60 backdrop-blur-sm p-8 rounded-2xl shadow-xl border border-gray-100">
                <!-- Email Input -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input wire:model="email" type="email" id="email" class="peer w-full pl-12 pr-4 py-4 bg-gray-50/80 border-2 border-gray-200 rounded-xl outline-none focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-300 @error('email') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror" placeholder=" " required>
                    <label for="email" class="absolute left-12 top-4 text-gray-500 text-sm transition-all duration-300 transform -translate-y-7 scale-90 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-7 bg-white px-2">Email Address</label>
                    @error('email') <p class="text-red-500 text-xs mt-2 ml-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                </div>

                <!-- Password Input -->
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input wire:model="password" type="password" id="password" class="peer w-full pl-12 pr-4 py-4 bg-gray-50/80 border-2 border-gray-200 rounded-xl outline-none focus:bg-white focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all duration-300 @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/10 @enderror" placeholder=" " required>
                    <label for="password" class="absolute left-12 top-4 text-gray-500 text-sm transition-all duration-300 transform -translate-y-7 scale-90 origin-[0] peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-90 peer-focus:-translate-y-7 bg-white px-2">Password</label>
                    @error('password') <p class="text-red-500 text-xs mt-2 ml-1 flex items-center"><svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center space-x-2 cursor-pointer group">
                        <input type="checkbox" class="w-4 h-4 rounded border-gray-300 text-primary focus:ring-2 focus:ring-primary focus:ring-offset-0 transition-colors">
                        <span class="text-sm text-gray-600 group-hover:text-gray-900 transition-colors">Remember me</span>
                    </label>
                    <a href="#" class="text-sm font-semibold text-primary hover:text-blue-700 transition-colors">Forgot password?</a>
                </div>

                <button type="submit" class="w-full py-4 px-4 bg-gradient-to-r from-primary to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg shadow-primary/30 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-primary/20">
                    <span wire:loading.remove class="flex items-center justify-center">
                        <span>Sign In</span>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                    <span wire:loading class="flex items-center justify-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </form>
            
            <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} PT. Parthaistic Kreasi Mendunia. All rights reserved.
                </p>
            </div>
        </div>
    </div>
</div>
