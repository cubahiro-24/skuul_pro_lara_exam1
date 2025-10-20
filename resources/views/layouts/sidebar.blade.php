<!-- Sidebar futuriste avec effets néon -->
<aside 
    x-data="{ sidebarOpen: true }" 
    :class="sidebarOpen ? 'w-64' : 'w-20'" 
    class="relative bg-gray-900/50 backdrop-blur-xl border-r border-cyan-500/30 shadow-[0_0_30px_rgba(6,182,212,0.3)] transition-all duration-300 ease-in-out"
>
    <!-- Logo Header -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-cyan-500/20">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.5)]">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                </svg>
            </div>
            <span x-show="sidebarOpen" x-cloak class="font-orbitron font-bold text-xl bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                HOSPITAL
            </span>
        </div>
        <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-lg hover:bg-cyan-500/10 transition-colors">
            <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 px-3 space-y-2">
        @php
            $menus = \App\Models\Menu::whereJsonContains('visible_pour', auth()->user()->role_id)
                ->whereNull('parent_id')
                ->orderBy('ordre')
                ->get();
        @endphp

        @foreach($menus as $menu)
            <a href="{{ $menu->lien }}" 
               class="group flex items-center space-x-3 px-4 py-3 rounded-xl transition-all duration-200
                      {{ request()->is(trim($menu->lien, '/') . '*') 
                          ? 'bg-gradient-to-r from-cyan-500/20 to-blue-500/20 border border-cyan-500/50 shadow-[0_0_15px_rgba(6,182,212,0.3)]' 
                          : 'hover:bg-cyan-500/10 border border-transparent' }}">
                <span class="text-cyan-400 group-hover:text-cyan-300 transition-colors">
                    @if($menu->icone)
                        @include('components.icons.' . $menu->icone)
                    @endif
                </span>
                <span x-show="sidebarOpen" x-cloak class="font-medium text-gray-200 group-hover:text-white transition-colors">
                    {{ $menu->titre }}
                </span>
                @if(request()->is(trim($menu->lien, '/') . '*'))
                    <span class="ml-auto w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                @endif
            </a>
        @endforeach
    </nav>

    <!-- User Profile (Bottom) -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-cyan-500/20">
        <div class="flex items-center space-x-3 px-3 py-2 rounded-xl bg-gradient-to-r from-purple-500/10 to-pink-500/10 border border-purple-500/30">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div x-show="sidebarOpen" x-cloak class="flex-1 min-w-0">
                <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-400 truncate">{{ auth()->user()->role?->nom }}</p>
            </div>
        </div>
    </div>

    <!-- Glow Effect -->
    <div class="absolute -top-20 -left-20 w-40 h-40 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
    <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse animation-delay-2000"></div>
</aside>

<style>
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
