<!-- Navbar futuriste -->
<header class="h-16 bg-gray-900/30 backdrop-blur-xl border-b border-cyan-500/20 shadow-[0_0_20px_rgba(6,182,212,0.2)]">
    <div class="h-full px-6 flex items-center justify-between">
        <!-- Search Bar -->
        <div class="flex-1 max-w-2xl">
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="Rechercher..."
                    class="w-full px-4 py-2 pl-10 bg-gray-800/50 border border-cyan-500/30 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-200"
                >
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <!-- Right Side Icons -->
        <div class="flex items-center space-x-4 ml-6">
            <!-- Quick Login/Logout Button -->
            @auth
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button 
                    type="submit" 
                    class="px-4 py-2 rounded-xl bg-red-500/20 border border-red-500/50 text-red-400 hover:bg-red-500/30 transition-all flex items-center space-x-2 font-medium"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
            @else
            <a 
                href="{{ route('login') }}" 
                class="px-4 py-2 rounded-xl bg-green-500/20 border border-green-500/50 text-green-400 hover:bg-green-500/30 transition-all flex items-center space-x-2 font-medium"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                <span>Login</span>
            </a>
            @endauth

            <!-- Notifications -->
            <div x-data="{ notifOpen: false }" class="relative">
                <button 
                    @click="notifOpen = !notifOpen"
                    class="relative p-2 rounded-xl bg-gray-800/50 border border-cyan-500/30 hover:bg-cyan-500/10 transition-all duration-200 group"
                >
                    <svg class="w-6 h-6 text-cyan-400 group-hover:text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-pink-500 to-red-500 rounded-full flex items-center justify-center text-xs font-bold shadow-[0_0_10px_rgba(236,72,153,0.5)]">
                        3
                    </span>
                </button>

                <!-- Notifications Dropdown -->
                <div 
                    x-show="notifOpen" 
                    @click.away="notifOpen = false"
                    x-cloak
                    x-transition
                    class="absolute right-0 mt-2 w-80 bg-gray-900/95 backdrop-blur-xl border border-cyan-500/30 rounded-2xl shadow-[0_0_30px_rgba(6,182,212,0.3)] overflow-hidden z-50"
                >
                    <div class="p-4 border-b border-cyan-500/20">
                        <h3 class="font-semibold text-white">Notifications</h3>
                    </div>
                    <div class="max-h-96 overflow-y-auto">
                        <a href="#" class="block p-4 hover:bg-cyan-500/10 transition-colors border-b border-gray-800">
                            <p class="text-sm text-white font-medium">Nouveau rendez-vous confirmé</p>
                            <p class="text-xs text-gray-400 mt-1">Il y a 5 minutes</p>
                        </a>
                        <a href="#" class="block p-4 hover:bg-cyan-500/10 transition-colors border-b border-gray-800">
                            <p class="text-sm text-white font-medium">Paiement reçu</p>
                            <p class="text-xs text-gray-400 mt-1">Il y a 1 heure</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <button 
                @click="darkMode = !darkMode"
                class="p-2 rounded-xl bg-gray-800/50 border border-cyan-500/30 hover:bg-cyan-500/10 transition-all duration-200"
            >
                <svg x-show="darkMode" class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg x-show="!darkMode" x-cloak class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>

            <!-- User Menu -->
            <div x-data="{ userMenuOpen: false }" class="relative">
                <button 
                    @click="userMenuOpen = !userMenuOpen"
                    class="flex items-center space-x-3 p-2 rounded-xl bg-gray-800/50 border border-cyan-500/30 hover:bg-cyan-500/10 transition-all duration-200"
                >
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                        <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                    </div>
                    <span class="text-white text-sm font-medium hidden md:block">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- User Dropdown -->
                <div 
                    x-show="userMenuOpen" 
                    @click.away="userMenuOpen = false"
                    x-cloak
                    x-transition
                    class="absolute right-0 mt-2 w-64 bg-gray-900/95 backdrop-blur-xl border border-cyan-500/30 rounded-2xl shadow-[0_0_30px_rgba(6,182,212,0.3)] overflow-hidden z-50"
                >
                    <div class="p-4 border-b border-cyan-500/20">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }} {{ auth()->user()->prenom }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                        <p class="text-xs text-cyan-400 mt-1">{{ auth()->user()->role?->nom }}</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 hover:bg-cyan-500/10 transition-colors text-sm text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>Mon Profil</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 px-4 py-3 hover:bg-cyan-500/10 transition-colors text-sm text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Paramètres</span>
                    </a>
                    <div class="border-t border-cyan-500/20">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-red-500/10 transition-colors text-sm text-red-400 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
