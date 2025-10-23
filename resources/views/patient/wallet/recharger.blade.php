@extends('layouts.app')

@section('title', 'Recharger le Portefeuille')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center space-x-4">
        <a href="{{ route('patient.wallet.index') }}" class="p-2 rounded-xl bg-gray-800/50 border border-cyan-500/30 hover:bg-cyan-500/10 transition-all">
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Recharger Mon Portefeuille
            </h1>
            <p class="text-gray-400 text-sm mt-1">Solde actuel: {{ $wallet->solde_formate }}</p>
        </div>
    </div>

    <!-- Rechargement Form -->
    <x-card>
        <x-slot name="header">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Formulaire de Rechargement</h3>
                    <p class="text-xs text-gray-400">Min: 1,000 FBU - Max: 5,000,000 FBU</p>
                </div>
            </div>
        </x-slot>

        <form action="{{ route('patient.wallet.store-rechargement') }}" method="POST" x-data="{ methode: 'mobile_money', montant: '' }">
            @csrf

            <!-- Montant -->
            <div class="mb-6">
                <label for="montant" class="block text-sm font-medium text-gray-300 mb-2">
                    Montant à Recharger (FBU) <span class="text-red-400">*</span>
                </label>
                <input 
                    type="number" 
                    id="montant" 
                    name="montant" 
                    x-model="montant"
                    min="1000"
                    max="5000000"
                    step="1000"
                    value="{{ old('montant') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                    placeholder="Ex: 50000"
                >
                @error('montant')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <div class="mt-3 flex flex-wrap gap-2">
                    <button type="button" @click="montant = '10000'" class="px-4 py-2 rounded-lg bg-gray-700/50 text-sm text-gray-300 hover:bg-cyan-500/20 hover:text-cyan-400 transition-all">
                        10,000 FBU
                    </button>
                    <button type="button" @click="montant = '25000'" class="px-4 py-2 rounded-lg bg-gray-700/50 text-sm text-gray-300 hover:bg-cyan-500/20 hover:text-cyan-400 transition-all">
                        25,000 FBU
                    </button>
                    <button type="button" @click="montant = '50000'" class="px-4 py-2 rounded-lg bg-gray-700/50 text-sm text-gray-300 hover:bg-cyan-500/20 hover:text-cyan-400 transition-all">
                        50,000 FBU
                    </button>
                    <button type="button" @click="montant = '100000'" class="px-4 py-2 rounded-lg bg-gray-700/50 text-sm text-gray-300 hover:bg-cyan-500/20 hover:text-cyan-400 transition-all">
                        100,000 FBU
                    </button>
                    <button type="button" @click="montant = '200000'" class="px-4 py-2 rounded-lg bg-gray-700/50 text-sm text-gray-300 hover:bg-cyan-500/20 hover:text-cyan-400 transition-all">
                        200,000 FBU
                    </button>
                </div>
            </div>

            <!-- Méthode de Paiement -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-3">
                    Méthode de Paiement <span class="text-red-400">*</span>
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <!-- Mobile Money -->
                    <label class="cursor-pointer">
                        <input type="radio" name="methode" value="mobile_money" x-model="methode" checked class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 transition-all peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 border-gray-700 hover:border-cyan-500/50">
                            <div class="flex flex-col items-center text-center">
                                <svg class="w-8 h-8 mb-2 peer-checked:text-cyan-400 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-white">Mobile Money</span>
                                <span class="text-xs text-gray-400 mt-1">Ecocash, Lumicash</span>
                            </div>
                        </div>
                    </label>

                    <!-- Carte Bancaire -->
                    <label class="cursor-pointer">
                        <input type="radio" name="methode" value="carte_bancaire" x-model="methode" class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 transition-all peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 border-gray-700 hover:border-cyan-500/50">
                            <div class="flex flex-col items-center text-center">
                                <svg class="w-8 h-8 mb-2 peer-checked:text-cyan-400 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                <span class="text-sm font-medium text-white">Carte Bancaire</span>
                                <span class="text-xs text-gray-400 mt-1">Visa, Mastercard</span>
                            </div>
                        </div>
                    </label>

                    <!-- Espèces -->
                    <label class="cursor-pointer">
                        <input type="radio" name="methode" value="especes" x-model="methode" class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 transition-all peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 border-gray-700 hover:border-cyan-500/50">
                            <div class="flex flex-col items-center text-center">
                                <svg class="w-8 h-8 mb-2 peer-checked:text-cyan-400 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-sm font-medium text-white">Espèces</span>
                                <span class="text-xs text-gray-400 mt-1">À la caisse</span>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Mobile Money Details -->
            <div x-show="methode === 'mobile_money'" x-transition class="mb-6">
                <label for="telephone" class="block text-sm font-medium text-gray-300 mb-2">
                    Numéro de Téléphone <span class="text-red-400">*</span>
                </label>
                <input 
                    type="tel" 
                    id="telephone" 
                    name="telephone" 
                    value="{{ old('telephone') }}"
                    placeholder="Ex: +257 79 123 456"
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                @error('telephone')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Carte Bancaire Details -->
            <div x-show="methode === 'carte_bancaire'" x-transition class="mb-6">
                <label for="numero_carte" class="block text-sm font-medium text-gray-300 mb-2">
                    Numéro de Carte <span class="text-red-400">*</span>
                </label>
                <input 
                    type="text" 
                    id="numero_carte" 
                    name="numero_carte" 
                    value="{{ old('numero_carte') }}"
                    placeholder="1234 5678 9012 3456"
                    maxlength="19"
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                @error('numero_carte')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Espèces Info -->
            <div x-show="methode === 'especes'" x-transition class="mb-6 p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/50 text-yellow-400">
                <div class="flex items-start space-x-3">
                    <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        <p class="font-medium">Information</p>
                        <p class="text-sm mt-1">Vous devrez vous présenter à la caisse de l'hôpital pour effectuer le rechargement en espèces.</p>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-cyan-500/20">
                <a 
                    href="{{ route('patient.wallet.index') }}" 
                    class="px-6 py-3 rounded-xl bg-gray-700/50 border border-gray-600/50 text-gray-300 hover:bg-gray-700 transition-all"
                >
                    Annuler
                </a>
                <button 
                    type="submit" 
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-all"
                >
                    Confirmer le Rechargement
                </button>
            </div>
        </form>
    </x-card>

    <!-- Security Info -->
    <x-card>
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-2">Paiement Sécurisé</h4>
                <ul class="text-sm text-gray-400 space-y-1">
                    <li>• Toutes les transactions sont cryptées et sécurisées</li>
                    <li>• Vos informations bancaires ne sont jamais stockées</li>
                    <li>• Vous recevrez une confirmation par SMS et email</li>
                    <li>• En cas de problème, contactez notre service client</li>
                </ul>
            </div>
        </div>
    </x-card>
</div>
@endsection
