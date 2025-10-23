@extends('layouts.app')

@section('title', 'Détails du Paiement')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.paiements.index') }}" class="text-cyan-400 hover:text-cyan-300 text-sm mb-2 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour à la liste
            </a>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Détails du Paiement
            </h1>
            <p class="text-gray-400 mt-1">Référence : {{ $paiement->reference }}</p>
        </div>
        
        <!-- Statut Badge -->
        @php
        $statusColors = [
            'reussi' => 'bg-green-500/20 text-green-400 border-green-500/50',
            'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
            'echoue' => 'bg-red-500/20 text-red-400 border-red-500/50',
        ];
        @endphp
        <span class="px-4 py-2 rounded-full text-sm font-medium border {{ $statusColors[$paiement->statut] ?? '' }}">
            {{ ucfirst($paiement->statut) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations du paiement -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">💳 Informations du Paiement</h3>
                </x-slot>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-400 mb-1 block">Montant</label>
                            <p class="text-2xl font-bold text-cyan-400">{{ number_format($paiement->montant, 0, ',', ' ') }} FBU</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400 mb-1 block">Mode de paiement</label>
                            <div class="flex items-center space-x-2">
                                @if($paiement->mode === 'wallet')
                                    <span class="text-cyan-400 text-2xl">💳</span>
                                @elseif($paiement->mode === 'mobile_money')
                                    <span class="text-green-400 text-2xl">📱</span>
                                @elseif($paiement->mode === 'carte_bancaire')
                                    <span class="text-blue-400 text-2xl">💳</span>
                                @else
                                    <span class="text-yellow-400 text-2xl">💵</span>
                                @endif
                                <span class="text-white text-lg capitalize">{{ str_replace('_', ' ', $paiement->mode) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-400 mb-1 block">Référence</label>
                            <p class="text-white font-mono">{{ $paiement->reference }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400 mb-1 block">Date de paiement</label>
                            <p class="text-white">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Informations du patient -->
            @if($paiement->rendezVous && $paiement->rendezVous->utilisateur)
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">👤 Informations du Patient</h3>
                </x-slot>

                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xl">{{ substr($paiement->rendezVous->utilisateur->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-white">{{ $paiement->rendezVous->utilisateur->name }} {{ $paiement->rendezVous->utilisateur->prenom }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            @if($paiement->rendezVous->utilisateur->email)
                            <div>
                                <label class="text-sm text-gray-400 mb-1 block">Email</label>
                                <p class="text-white">{{ $paiement->rendezVous->utilisateur->email }}</p>
                            </div>
                            @endif
                            @if($paiement->rendezVous->utilisateur->telephone)
                            <div>
                                <label class="text-sm text-gray-400 mb-1 block">Téléphone</label>
                                <p class="text-white">{{ $paiement->rendezVous->utilisateur->telephone }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>
            @endif

            <!-- Informations du rendez-vous -->
            @if($paiement->rendezVous)
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">📅 Rendez-vous Associé</h3>
                </x-slot>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-sm text-gray-400 mb-1 block">Date</label>
                            <p class="text-white">{{ \Carbon\Carbon::parse($paiement->rendezVous->date_rdv)->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm text-gray-400 mb-1 block">Heure</label>
                            <p class="text-white">{{ \Carbon\Carbon::parse($paiement->rendezVous->heure_rdv)->format('H:i') }}</p>
                        </div>
                    </div>

                    @if($paiement->rendezVous->typeService)
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Service</label>
                        <div class="p-4 rounded-xl bg-gradient-to-r from-cyan-500/10 to-blue-500/10 border border-cyan-500/30">
                            <p class="text-white font-semibold">{{ $paiement->rendezVous->typeService->nom }}</p>
                            <p class="text-sm text-gray-400 mt-1">{{ number_format($paiement->rendezVous->typeService->prix, 0, ',', ' ') }} FBU</p>
                        </div>
                    </div>
                    @endif

                    @if($paiement->rendezVous->medecin)
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Médecin</label>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                                <span class="text-white font-bold text-sm">{{ substr($paiement->rendezVous->medecin->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">Dr. {{ $paiement->rendezVous->medecin->name }}</p>
                                <p class="text-xs text-gray-400">{{ $paiement->rendezVous->medecin->email }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </x-card>
            @endif
        </div>

        <!-- Colonne actions -->
        <div class="space-y-6">
            <!-- Facture -->
            @if($paiement->facture)
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">🧾 Facture</h3>
                </x-slot>

                <div class="space-y-3">
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Numéro</label>
                        <p class="text-white font-mono">{{ $paiement->facture->numero_facture }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Date d'émission</label>
                        <p class="text-white">{{ \Carbon\Carbon::parse($paiement->facture->date_emission)->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Montant</label>
                        <p class="text-cyan-400 font-bold">{{ number_format($paiement->facture->montant, 0, ',', ' ') }} FBU</p>
                    </div>
                </div>
            </x-card>
            @endif

            <!-- Informations système -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">ℹ️ Informations</h3>
                </x-slot>

                <div class="space-y-3 text-sm">
                    <div>
                        <label class="text-gray-400 block mb-1">Créé le</label>
                        <p class="text-white">{{ $paiement->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">Mis à jour le</label>
                        <p class="text-white">{{ $paiement->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
