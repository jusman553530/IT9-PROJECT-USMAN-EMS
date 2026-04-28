<header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-20">
    <div class="px-4 sm:px-6 py-3 flex justify-between items-center">
        <!-- Mobile Menu Button & Logo -->
        <div class="flex items-center gap-3">
            <div class="w-8 lg:hidden"></div>
            
            <img src="/logo.png" class="w-8 h-8 sm:w-10 sm:h-10 object-contain" alt="EMS Logo">
            <span class="hidden sm:inline text-lg lg:text-xl font-semibold text-gray-800 truncate">
                Employee Management System
            </span>
            <span class="sm:hidden text-base font-semibold text-gray-800">EMS</span>
        </div>

        <!-- User Dropdown -->
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" class="flex items-center space-x-2 sm:space-x-3 focus:outline-none hover:bg-gray-50 px-2 sm:px-3 py-2 rounded-lg transition">
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-green-600 flex items-center justify-center text-white font-semibold text-sm">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="hidden md:block text-left">
                    <p class="text-sm font-medium text-gray-800 truncate max-w-[120px]">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500 truncate max-w-[120px]">{{ Auth::user()->email ?? 'admin@ems.com' }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-1 z-50 border border-gray-200"
                 style="display: none;">
                
                <div class="px-4 py-3 border-b border-gray-200">
                    <p class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? 'Admin User' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? 'admin@ems.com' }}</p>
                </div>

                <!-- My Profile -->
                @php
                    $profileEmployee = App\Models\Employee::where('email', Auth::user()->email)->first();
                @endphp
                @if($profileEmployee)
                <a href="{{ route('profile.show') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    My Profile
                </a>
                @endif

                <div class="border-t border-gray-200"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Sign Out
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>