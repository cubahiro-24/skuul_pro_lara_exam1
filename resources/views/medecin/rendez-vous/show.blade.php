@extends('layouts.app')

@section('title', 'Détails du Rendez-vous')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('medecin.rendez-vous.index') }}" class="text-cyan-400 hover:text-cyan-300 text-sm mb-2 inline-flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Retour à la liste
            </a>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Détails du Rendez-vous
            </h1>
            <p class="text-gray-400 mt-1">Référence : #RDV-{{ str_pad($rendezVous->id, 6, '0', STR_PAD_LEFT) }}</p>
        </div>
        
        <!-- Statut Badge -->
        @php
        $statusColors = [
            'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
            'confirme' => 'bg-green-500/20 text-green-400 border-green-500/50',
            'annule' => 'bg-red-500/20 text-red-400 border-red-500/50',
            'termine' => 'bg-blue-500/20 text-blue-400 border-blue-500/50',
        ];
        @endphp
        <span class="px-4 py-2 rounded-full text-sm font-medium border {{ $statusColors[$rendezVous->statut] ?? '' }}">
            {{ ucfirst(str_replace('_', ' ', $rendezVous->statut)) }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations du rendez-vous -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">📅 Informations du Rendez-vous</h3>
                </x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Date</label>
                        <p class="text-white text-lg font-medium">{{ \Carbon\Carbon::parse($rendezVous->date_rdv)->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($rendezVous->date_rdv)->isoFormat('dddd') }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-400 mb-1 block">Heure</label>
                        <p class="text-white text-lg font-medium">🕐 {{ \Carbon\Carbon::parse($rendezVous->heure_rdv)->format('H:i') }}</p>
                    </div>
                </div>
            </x-card>

            <!-- Informations du patient -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">👤 Informations du Patient</h3>
                </x-slot>

                <div class="flex items-start space-x-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-xl">{{ substr($rendezVous->utilisateur->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-white">{{ $rendezVous->utilisateur->name }} {{ $rendezVous->utilisateur->prenom }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            @if($rendezVous->utilisateur->email)
                            <div>
                                <label class="text-sm text-gray-400 mb-1 block">Email</label>
                                <p class="text-white">{{ $rendezVous->utilisateur->email }}</p>
                            </div>
                            @endif
                            @if($rendezVous->utilisateur->telephone)
                            <div>
                                <label class="text-sm text-gray-400 mb-1 block">Téléphone</label>
                                <p class="text-white">{{ $rendezVous->utilisateur->telephone }}</p>
                            </div>
                            @endif
                            @if($rendezVous->utilisateur->adresse)
                            <div class="md:col-span-2">
                                <label class="text-sm text-gray-400 mb-1 block">Adresse</label>
                                <p class="text-white">{{ $rendezVous->utilisateur->adresse }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Service et Prix -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">🏥 Service Demandé</h3>
                </x-slot>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 rounded-xl bg-gradient-to-r from-cyan-500/10 to-blue-500/10 border border-cyan-500/30">
                        <div>
                            <h4 class="text-white font-semibold text-lg">{{ $rendezVous->typeService->nom }}</h4>
                            @if($rendezVous->typeService->service)
                            <p class="text-gray-400 text-sm">{{ $rendezVous->typeService->service->nom }}</p>
                            @endif
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-cyan-400">{{ number_format($rendezVous->typeService->prix, 0, ',', ' ') }}</p>
                            <p class="text-sm text-gray-400">FBU</p>
                        </div>
                    </div>

                    @if($rendezVous->notes)
                    <div>
                        <label class="text-sm text-gray-400 mb-2 block">Notes / Symptômes</label>
                        <div class="p-4 rounded-lg bg-gray-800/50 border border-cyan-500/20">
                            <p class="text-white">{{ $rendezVous->notes }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Informations de paiement -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">💳 Paiement</h3>
                </x-slot>

                @if($rendezVous->paiements->isEmpty())
                    <div class="text-center py-8">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-gray-500/20 to-gray-600/20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-gray-400">Aucun paiement enregistré</p>
                    </div>
                @else
                    @foreach($rendezVous->paiements as $paiement)
                    <div class="p-4 rounded-xl bg-gray-800/50 border border-cyan-500/20 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Référence</span>
                            <span class="text-white font-mono">{{ $paiement->reference }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Montant</span>
                            <span class="text-white font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} FBU</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Mode</span>
                            <span class="text-white capitalize flex items-center">
                                @if($paiement->mode === 'wallet')
                                    <span class="text-cyan-400 mr-2">💳</span>
                                @endif
                                {{ str_replace('_', ' ', $paiement->mode) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Statut</span>
                            @php
                            $paiementColors = [
                                'reussi' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                                'echoue' => 'bg-red-500/20 text-red-400 border-red-500/50',
                            ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $paiementColors[$paiement->statut] ?? '' }}">
                                {{ ucfirst($paiement->statut) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400">Date</span>
                            <span class="text-white">{{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}</span>
                        </div>

                        @if($paiement->facture)
                        <div class="pt-3 border-t border-cyan-500/20">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-400">Facture</span>
                                <span class="text-cyan-400 font-mono">{{ $paiement->facture->numero_facture }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </x-card>
        </div>

        <!-- Colonne actions -->
        <div class="space-y-6">
            <!-- Actions rapides -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">⚡ Actions</h3>
                </x-slot>

                <div class="space-y-3">
                    @if($rendezVous->statut === 'en_attente')
                    <!-- Confirmer -->
                    <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rendezVous) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="statut" value="confirme">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white rounded-lg font-medium hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-all flex items-center justify-center"
                                onclick="return confirm('Confirmer ce rendez-vous ?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Confirmer le RDV
                        </button>
                    </form>

                    <!-- Annuler -->
                    <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rendezVous) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="statut" value="annule">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-red-500/20 border-2 border-red-500/50 text-red-400 rounded-lg font-medium hover:bg-red-500/30 transition-all flex items-center justify-center"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler le RDV
                        </button>
                    </form>
                    @endif

                    @if($rendezVous->statut === 'confirme')
                    <!-- Terminer -->
                    <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rendezVous) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="statut" value="termine">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-gradient-to-r from-blue-500 to-cyan-500 text-white rounded-lg font-medium hover:shadow-[0_0_30px_rgba(59,130,246,0.5)] transition-all flex items-center justify-center"
                                onclick="return confirm('Marquer ce rendez-vous comme terminé ?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Marquer comme terminé
                        </button>
                    </form>

                    <!-- Annuler -->
                    <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rendezVous) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="statut" value="annule">
                        <button type="submit" 
                                class="w-full px-4 py-3 bg-red-500/20 border-2 border-red-500/50 text-red-400 rounded-lg font-medium hover:bg-red-500/30 transition-all flex items-center justify-center"
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?')">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Annuler le RDV
                        </button>
                    </form>
                    @endif

                    @if(in_array($rendezVous->statut, ['termine', 'annule']))
                    <div class="p-4 rounded-lg bg-gray-800/50 border border-cyan-500/20 text-center">
                        <p class="text-gray-400 text-sm">
                            @if($rendezVous->statut === 'termine')
                            ✅ Rendez-vous terminé
                            @else
                            ❌ Rendez-vous annulé
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Informations supplémentaires -->
            <x-card>
                <x-slot name="header">
                    <h3 class="text-lg font-semibold text-white">ℹ️ Informations</h3>
                </x-slot>

                <div class="space-y-3 text-sm">
                    <div>
                        <label class="text-gray-400 block mb-1">Créé le</label>
                        <p class="text-white">{{ $rendezVous->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <label class="text-gray-400 block mb-1">Mis à jour le</label>
                        <p class="text-white">{{ $rendezVous->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </x-card>
        </div>
    </div>
</div>
@endsection
