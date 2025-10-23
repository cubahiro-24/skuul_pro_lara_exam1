<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hospital Pro - Gestion des Rendez-vous</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Orbitron:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-br from-gray-900 via-purple-900 to-blue-900 min-h-screen text-white">
    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-gray-900/50 backdrop-blur-xl border-b border-cyan-500/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-[0_0_20px_rgba(6,182,212,0.5)]">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                    <span class="font-orbitron font-bold text-xl bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                        HOSPITAL PRO
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-2 rounded-xl bg-cyan-500/20 border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/30 font-semibold transition-all">
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="px-6 py-2 rounded-xl bg-red-500/20 border border-red-500/50 text-red-400 hover:bg-red-500/30 font-semibold transition-all flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2 rounded-xl border-2 border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/10 font-semibold transition-all">
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-semibold shadow-[0_0_20px_rgba(6,182,212,0.4)] transition-all">
                            S'inscrire
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4">
        <div class="max-w-7xl mx-auto text-center">
            @auth
            <!-- Logged in user info -->
            <div class="mb-6 p-4 max-w-md mx-auto rounded-xl bg-green-500/10 border border-green-500/30">
                <p class="text-green-400 font-semibold">✅ Connecté en tant que: <span class="text-white">{{ auth()->user()->email }}</span></p>
                <p class="text-sm text-gray-400 mt-1">Rôle: <span class="text-cyan-400">{{ auth()->user()->role?->nom }}</span></p>
            </div>
            @endauth
            
            <h1 class="text-6xl md:text-7xl font-bold font-orbitron mb-6 bg-gradient-to-r from-cyan-400 via-blue-400 to-purple-400 bg-clip-text text-transparent animate-pulse">
                HOSPITAL PRO
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-3xl mx-auto">
                Système de gestion des rendez-vous et paiements hospitaliers de nouvelle génération
            </p>
            
            @guest
            <!-- Quick Login Section for Guests -->
            <div class="mb-8 p-6 max-w-2xl mx-auto rounded-2xl bg-gradient-to-r from-cyan-500/10 to-blue-500/10 border border-cyan-500/30">
                <h3 class="text-lg font-semibold text-cyan-400 mb-4">🔑 Connexion Rapide</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <form method="POST" action="{{ route('login') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="admin@hospital.com">
                        <input type="hidden" name="password" value="admin123">
                        <button type="submit" class="w-full px-4 py-3 rounded-xl bg-red-500/20 border border-red-500/50 text-red-300 hover:bg-red-500/30 transition-all">
                            <div class="font-bold">👨‍💼 Admin</div>
                            <div class="text-xs mt-1">admin@hospital.com</div>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('login') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="medecin@hospital.com">
                        <input type="hidden" name="password" value="medecin123">
                        <button type="submit" class="w-full px-4 py-3 rounded-xl bg-blue-500/20 border border-blue-500/50 text-blue-300 hover:bg-blue-500/30 transition-all">
                            <div class="font-bold">👨‍⚕️ Médecin</div>
                            <div class="text-xs mt-1">medecin@hospital.com</div>
                        </button>
                    </form>
                    <form method="POST" action="{{ route('login') }}" class="inline">
                        @csrf
                        <input type="hidden" name="email" value="patient@hospital.com">
                        <input type="hidden" name="password" value="patient123">
                        <button type="submit" class="w-full px-4 py-3 rounded-xl bg-green-500/20 border border-green-500/50 text-green-300 hover:bg-green-500/30 transition-all">
                            <div class="font-bold">🤒 Patient</div>
                            <div class="text-xs mt-1">patient@hospital.com</div>
                        </button>
                    </form>
                </div>
                <p class="text-xs text-gray-400 mt-3">Ou <a href="{{ route('login') }}" class="text-cyan-400 hover:underline">connectez-vous manuellement</a></p>
            </div>
            @endguest

            <div class="flex items-center justify-center space-x-4">
                @guest
                    <a href="{{ route('register') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold text-lg shadow-[0_0_30px_rgba(6,182,212,0.5)] hover:scale-105 transform transition-all">
                        Prendre Rendez-vous
                    </a>
                @else
                    <a href="{{ route('patient.rendez-vous.create') }}" class="px-8 py-4 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-600 hover:to-blue-600 text-white font-bold text-lg shadow-[0_0_30px_rgba(6,182,212,0.5)] hover:scale-105 transform transition-all">
                        Prendre Rendez-vous
                    </a>
                @endguest
                <a href="#services" class="px-8 py-4 rounded-xl border-2 border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/10 font-bold text-lg transition-all">
                    Nos Services
                </a>
            </div>
        </div>

        <!-- Floating Elements -->
        <div class="absolute top-20 left-10 w-32 h-32 bg-cyan-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute top-40 right-10 w-32 h-32 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-orbitron mb-4 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                    Nos Services
                </h2>
                <p class="text-gray-400 text-lg">Des soins de qualité pour votre santé</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($services as $service)
                <div class="group bg-gray-900/40 backdrop-blur-xl border border-cyan-500/30 rounded-2xl p-6 hover:border-cyan-500 transition-all duration-300 hover:shadow-[0_0_30px_rgba(6,182,212,0.3)] hover:scale-105">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center mb-4 group-hover:shadow-[0_0_20px_rgba(6,182,212,0.4)] transition-shadow">
                        <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ $service->nom }}</h3>
                    <p class="text-gray-400 mb-4">{{ $service->description }}</p>
                    
                    <div class="space-y-2">
                        @foreach($service->typeServices->take(3) as $type)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-300">{{ $type->nom }}</span>
                            <span class="text-cyan-400 font-semibold">{{ number_format($type->prix, 0, ',', ' ') }} FBU</span>
                        </div>
                        @endforeach
                    </div>

                    @guest
                        <a href="{{ route('register') }}" class="mt-6 block text-center px-4 py-2 rounded-lg bg-cyan-500/10 border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/20 transition-colors">
                            Réserver
                        </a>
                    @else
                        <a href="{{ route('patient.rendez-vous.create') }}" class="mt-6 block text-center px-4 py-2 rounded-lg bg-cyan-500/10 border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/20 transition-colors">
                            Réserver
                        </a>
                    @endguest
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 px-4 bg-gray-900/30">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold font-orbitron mb-4 bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                    Pourquoi Nous Choisir ?
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-8">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Prise de RDV Rapide</h3>
                    <p class="text-gray-400">Réservez votre rendez-vous en quelques clics</p>
                </div>

                <div class="text-center p-8">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Paiement Sécurisé</h3>
                    <p class="text-gray-400">Plusieurs modes de paiement disponibles</p>
                </div>

                <div class="text-center p-8">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Soins de Qualité</h3>
                    <p class="text-gray-400">Personnel médical qualifié et expérimenté</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-4 border-t border-cyan-500/20">
        <div class="max-w-7xl mx-auto text-center">
            <p class="text-gray-400">&copy; {{ date('Y') }} Hospital Pro. Tous droits réservés.</p>
        </div>
    </footer>

    <style>
        .animation-delay-2000 {
            animation-delay: 2s;
        }
    </style>
</body>
</html>
