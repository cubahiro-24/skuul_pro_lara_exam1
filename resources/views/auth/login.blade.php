<x-guest-layout>
    <!-- Header -->
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
            Connexion
        </h2>
        <p class="text-sm text-gray-400 mt-2">Connectez-vous à votre espace</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                Adresse Email
            </label>
            <input 
                id="email" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username"
                class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                placeholder="votre@email.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                Mot de passe
            </label>
            <input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input 
                    id="remember_me" 
                    type="checkbox" 
                    class="rounded border-cyan-500/30 bg-gray-800/50 text-cyan-500 focus:ring-cyan-500/50" 
                    name="remember"
                />
                <span class="ms-2 text-sm text-gray-400">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button 
                type="submit" 
                class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all"
            >
                Se connecter
            </button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
        <div class="text-center pt-4 border-t border-cyan-500/20">
            <p class="text-sm text-gray-400">
                Pas encore de compte ?
                <a href="{{ route('register') }}" class="text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
                    Créer un compte
                </a>
            </p>
        </div>
        @endif
    </form>

    <!-- Test Credentials Info -->
    <div class="mt-6 p-4 rounded-xl bg-blue-500/10 border border-blue-500/30">
        <p class="text-xs text-blue-300 font-semibold mb-2">🔑 Comptes de test :</p>
        <div class="space-y-1 text-xs text-gray-400">
            <p><span class="text-cyan-400">Admin:</span> admin@hospital.com / admin123</p>
            <p><span class="text-green-400">Patient:</span> patient@hospital.com / patient123</p>
            <p><span class="text-purple-400">Médecin:</span> medecin@hospital.com / medecin123</p>
        </div>
    </div>
</x-guest-layout>
