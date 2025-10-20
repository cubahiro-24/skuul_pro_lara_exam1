@extends('layouts.app')

@section('title', 'Modifier un Utilisateur')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold font-orbitron text-white">Modifier l'utilisateur</h2>
                <a href="{{ route('admin.utilisateurs.index') }}" class="text-cyan-400 hover:text-cyan-300">
                    ← Retour
                </a>
            </div>
        </x-slot>

        <form action="{{ route('admin.utilisateurs.update', $utilisateur) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                        Nom <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="{{ old('name', $utilisateur->name) }}" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    >
                    @error('name')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prénom -->
                <div>
                    <label for="prenom" class="block text-sm font-medium text-gray-300 mb-2">
                        Prénom <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="prenom" 
                        name="prenom" 
                        value="{{ old('prenom', $utilisateur->prenom) }}" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    >
                    @error('prenom')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                    Email <span class="text-red-400">*</span>
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $utilisateur->email) }}" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                @error('email')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                        Nouveau mot de passe (laisser vide pour ne pas modifier)
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    >
                    @error('password')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                        Confirmer le mot de passe
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Téléphone -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-300 mb-2">
                        Téléphone
                    </label>
                    <input 
                        type="text" 
                        id="telephone" 
                        name="telephone" 
                        value="{{ old('telephone', $utilisateur->telephone) }}" 
                        class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    >
                    @error('telephone')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Rôle -->
                <div>
                    <label for="role_id" class="block text-sm font-medium text-gray-300 mb-2">
                        Rôle <span class="text-red-400">*</span>
                    </label>
                    <select 
                        id="role_id" 
                        name="role_id" 
                        required
                        class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    >
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $utilisateur->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->nom }}
                        </option>
                        @endforeach
                    </select>
                    @error('role_id')
                    <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Adresse -->
            <div>
                <label for="adresse" class="block text-sm font-medium text-gray-300 mb-2">
                    Adresse
                </label>
                <textarea 
                    id="adresse" 
                    name="adresse" 
                    rows="3"
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all resize-none"
                >{{ old('adresse', $utilisateur->adresse) }}</textarea>
                @error('adresse')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Statut -->
            <div>
                <label for="statut" class="block text-sm font-medium text-gray-300 mb-2">
                    Statut <span class="text-red-400">*</span>
                </label>
                <select 
                    id="statut" 
                    name="statut" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="actif" {{ old('statut', $utilisateur->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                    <option value="inactif" {{ old('statut', $utilisateur->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                </select>
                @error('statut')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a 
                    href="{{ route('admin.utilisateurs.index') }}" 
                    class="px-6 py-3 rounded-xl bg-gray-700/50 border border-gray-600/50 text-gray-300 hover:bg-gray-700 transition-all"
                >
                    Annuler
                </a>
                <button 
                    type="submit" 
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all"
                >
                    Mettre à jour
                </button>
            </div>
        </form>
    </x-card>
</div>
@endsection
