@extends('layouts.app')

@section('title', 'Prendre Rendez-vous')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card>
        <x-slot name="header">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold font-orbitron text-white">Prendre Rendez-vous</h2>
                    <p class="text-sm text-gray-400">Remplissez le formulaire pour réserver votre consultation</p>
                </div>
            </div>
        </x-slot>

        <form action="{{ route('patient.rendez-vous.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Service Selection -->
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Service <span class="text-red-400">*</span>
                </label>
                <select 
                    id="service_id" 
                    name="service_id" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="">-- Sélectionnez un service --</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->nom }}
                    </option>
                    @endforeach
                </select>
                @error('service_id')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type Service Selection -->
            <div>
                <label for="type_service_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Type de Service <span class="text-red-400">*</span>
                </label>
                <select 
                    id="type_service_id" 
                    name="type_service_id" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="">-- Sélectionnez d'abord un service --</option>
                </select>
                @error('type_service_id')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div>
                <label for="date_rdv" class="block text-sm font-medium text-gray-300 mb-2">
                    Date du Rendez-vous <span class="text-red-400">*</span>
                </label>
                <input 
                    type="date" 
                    id="date_rdv" 
                    name="date_rdv" 
                    min="{{ date('Y-m-d') }}"
                    value="{{ old('date_rdv') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                @error('date_rdv')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Time -->
            <div>
                <label for="heure_rdv" class="block text-sm font-medium text-gray-300 mb-2">
                    Heure du Rendez-vous <span class="text-red-400">*</span>
                </label>
                <select 
                    id="heure_rdv" 
                    name="heure_rdv" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="">-- Sélectionnez une heure --</option>
                    @for($h = 8; $h <= 17; $h++)
                        @foreach(['00', '30'] as $m)
                            @php
                            $time = sprintf('%02d:%s', $h, $m);
                            @endphp
                            @if($h < 17 || ($h == 17 && $m == '00'))
                            <option value="{{ $time }}" {{ old('heure_rdv') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                            @endif
                        @endforeach
                    @endfor
                </select>
                @error('heure_rdv')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Horaires d'ouverture: 08:00 - 17:00</p>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                    Notes / Symptômes (optionnel)
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4"
                    placeholder="Décrivez vos symptômes ou toute information utile pour le médecin..."
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all resize-none"
                >{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Paiement via Wallet -->
                        <!-- Option Paiement Wallet -->
            @if($wallet)
            <div x-data="{ 
                payerMaintenant: false, 
                selectedTypeService: null,
                prixService: 0,
                soldeWallet: {{ $wallet->solde }}
            }">
                <div class="border border-yellow-500/30 rounded-xl p-6 bg-gradient-to-br from-yellow-500/5 to-orange-500/5">
                    <div class="flex items-start space-x-3 mb-4">
                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white mb-1">Paiement via Portefeuille</h3>
                            <p class="text-sm text-gray-400">Votre solde actuel : <span class="text-yellow-400 font-semibold">{{ $wallet->solde_formate }}</span></p>
                        </div>
                    </div>

                    <label class="flex items-center space-x-3 cursor-pointer group">
                        <input 
                            type="checkbox" 
                            name="payer_maintenant" 
                            value="1"
                            x-model="payerMaintenant"
                            class="w-5 h-5 rounded border-cyan-500/30 bg-gray-800/50 text-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                        >
                        <span class="text-white group-hover:text-cyan-400 transition-colors">
                            Payer maintenant avec mon portefeuille
                        </span>
                    </label>

                    <div x-show="payerMaintenant" x-cloak class="mt-4 p-4 bg-gray-800/50 rounded-lg border border-cyan-500/20">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-400">Montant à payer :</span>
                            <span class="text-lg font-semibold text-white" x-text="prixService > 0 ? prixService.toLocaleString() + ' FBU' : 'Sélectionnez un service'"></span>
                        </div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-400">Votre solde :</span>
                            <span class="text-lg font-semibold" :class="soldeWallet >= prixService ? 'text-green-400' : 'text-red-400'" x-text="soldeWallet.toLocaleString() + ' FBU'"></span>
                        </div>
                        <div class="border-t border-cyan-500/20 pt-2 mt-2">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-300">Solde après paiement :</span>
                                <span class="text-lg font-bold" :class="(soldeWallet - prixService) >= 0 ? 'text-cyan-400' : 'text-red-400'" x-text="(soldeWallet - prixService).toLocaleString() + ' FBU'"></span>
                            </div>
                        </div>

                        <div x-show="prixService > 0 && soldeWallet < prixService" class="mt-3 p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                            <p class="text-sm text-red-400 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                                <span>Solde insuffisant ! <a href="{{ route('patient.wallet.recharger') }}" class="underline hover:text-red-300">Recharger mon portefeuille</a></span>
                            </p>
                        </div>

                        <div x-show="prixService > 0 && soldeWallet >= prixService" class="mt-3 p-3 bg-green-500/10 border border-green-500/30 rounded-lg">
                            <p class="text-sm text-green-400 flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Solde suffisant pour effectuer le paiement</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a 
                    href="{{ route('patient.dashboard') }}" 
                    class="px-6 py-3 rounded-xl bg-gray-700/50 border border-gray-600/50 text-gray-300 hover:bg-gray-700 transition-all"
                >
                    Annuler
                </a>
                <button 
                    type="submit" 
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all"
                >
                    Confirmer le Rendez-vous
                </button>
            </div>
        </form>
    </x-card>

    <!-- Information Card -->
    <x-card class="mt-6">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-2">Informations importantes</h4>
                <ul class="text-sm text-gray-400 space-y-1">
                    <li>• Votre rendez-vous sera confirmé par le personnel médical dans les 24 heures</li>
                    <li>• Veuillez arriver 10 minutes avant l'heure prévue</li>
                    <li>• En cas d'empêchement, merci d'annuler au moins 24h à l'avance</li>
                    <li>• N'oubliez pas d'apporter votre carte d'identité et vos documents médicaux</li>
                </ul>
            </div>
        </div>
    </x-card>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const serviceSelect = document.getElementById('service_id');
        const typeServiceSelect = document.getElementById('type_service_id');
        const oldServiceId = "{{ old('service_id') }}";
        const oldTypeServiceId = "{{ old('type_service_id') }}";
        
        // Fonction pour charger les types de services
        function loadTypeServices(serviceId, selectedTypeServiceId = null) {
            if (!serviceId) {
                typeServiceSelect.innerHTML = '<option value="">-- Sélectionnez d\'abord un service --</option>';
                return;
            }
            
            fetch(`/api/services/${serviceId}/type-services`)
                .then(r => r.json())
                .then(data => {
                    typeServiceSelect.innerHTML = '<option value="">-- Sélectionnez un type de service --</option>';
                    data.forEach(ts => {
                        const selected = ts.id == selectedTypeServiceId ? 'selected' : '';
                        typeServiceSelect.innerHTML += `<option value="${ts.id}" data-prix="${ts.prix}" ${selected}>${ts.nom} - ${ts.prix.toLocaleString()} FBU (${ts.duree_minutes} min)</option>`;
                    });
                    
                    // Mettre à jour le prix si une option était sélectionnée
                    if (selectedTypeServiceId) {
                        updatePrix();
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des types de services:', error);
                    typeServiceSelect.innerHTML = '<option value="">Erreur de chargement</option>';
                });
        }
        
        // Fonction pour mettre à jour le prix affiché
        function updatePrix() {
            const selectedOption = typeServiceSelect.options[typeServiceSelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.prix) {
                const prix = parseFloat(selectedOption.dataset.prix);
                // Mettre à jour via Alpine.js
                const alpineComponent = document.querySelector('[x-data]').__x.$data;
                if (alpineComponent) {
                    alpineComponent.prixService = prix;
                }
            }
        }
        
        // Écouter les changements sur le select de service
        serviceSelect.addEventListener('change', function() {
            loadTypeServices(this.value);
        });
        
        // Écouter les changements sur le select de type de service
        typeServiceSelect.addEventListener('change', updatePrix);
        
        // Si un service était sélectionné (old input), recharger les types de services
        if (oldServiceId) {
            loadTypeServices(oldServiceId, oldTypeServiceId);
        }
    });
</script>
@endpush
@endsection
