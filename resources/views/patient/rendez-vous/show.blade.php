@extends('layouts.app')

@section('title', 'Détails du Rendez-vous')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header avec retour -->
    <div class="flex items-center justify-between">
        <a 
            href="{{ route('patient.rendez-vous.index') }}" 
            class="inline-flex items-center space-x-2 text-cyan-400 hover:text-cyan-300 transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Retour à la liste</span>
        </a>

        @if(in_array($rendezVous->statut, ['en_attente', 'confirme']))
        <form 
            action="{{ route('patient.rendez-vous.destroy', $rendezVous) }}" 
            method="POST" 
            onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')"
        >
            @csrf
            @method('DELETE')
            <button 
                type="submit"
                class="px-4 py-2 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 transition-colors inline-flex items-center space-x-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span>Annuler le RDV</span>
            </button>
        </form>
        @endif
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/50 text-green-400">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/50 text-red-400">
        {{ session('error') }}
    </div>
    @endif

    <!-- Titre et Statut -->
    <x-card>
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                    Rendez-vous #{{ $rendezVous->id }}
                </h1>
                <p class="text-gray-400 mt-1">Détails de votre consultation</p>
            </div>
            <div>
                @php
                $statusConfig = [
                    'en_attente' => ['bg' => 'bg-yellow-500/20', 'text' => 'text-yellow-400', 'border' => 'border-yellow-500/50', 'label' => 'En attente'],
                    'confirme' => ['bg' => 'bg-green-500/20', 'text' => 'text-green-400', 'border' => 'border-green-500/50', 'label' => 'Confirmé'],
                    'annule' => ['bg' => 'bg-red-500/20', 'text' => 'text-red-400', 'border' => 'border-red-500/50', 'label' => 'Annulé'],
                    'termine' => ['bg' => 'bg-blue-500/20', 'text' => 'text-blue-400', 'border' => 'border-blue-500/50', 'label' => 'Terminé'],
                ];
                $status = $statusConfig[$rendezVous->statut] ?? $statusConfig['en_attente'];
                @endphp
                <span class="px-4 py-2 rounded-full text-sm font-medium border {{ $status['bg'] }} {{ $status['text'] }} {{ $status['border'] }}">
                    {{ $status['label'] }}
                </span>
            </div>
        </div>
    </x-card>

    <!-- Informations du Rendez-vous -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Date et Heure -->
        <x-card>
            <div class="flex items-start space-x-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-400 mb-1">Date et Heure</p>
                    <p class="text-xl font-bold text-white">{{ $rendezVous->date_rdv->format('d/m/Y') }}</p>
                    <p class="text-lg text-cyan-400">{{ \Carbon\Carbon::parse($rendezVous->heure_rdv)->format('H:i') }}</p>
                </div>
            </div>
        </x-card>

        <!-- Service -->
        <x-card>
            <div class="flex items-start space-x-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-400 mb-1">Service</p>
                    <p class="text-lg font-bold text-white">{{ $rendezVous->typeService->nom }}</p>
                    <p class="text-sm text-gray-400">{{ $rendezVous->typeService->service->nom }}</p>
                    <p class="text-cyan-400 font-semibold mt-1">{{ number_format($rendezVous->typeService->prix, 0, ',', ' ') }} FBU</p>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Médecin -->
    <x-card>
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-2xl">{{ substr($rendezVous->medecin->name, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-400 mb-1">Médecin assigné</p>
                <p class="text-xl font-bold text-white">Dr. {{ $rendezVous->medecin->name }}</p>
                <p class="text-sm text-gray-400">{{ $rendezVous->medecin->email }}</p>
                @if($rendezVous->medecin->telephone)
                <p class="text-sm text-cyan-400 mt-1">📞 {{ $rendezVous->medecin->telephone }}</p>
                @endif
            </div>
        </div>
    </x-card>

    <!-- Notes -->
    @if($rendezVous->notes)
    <x-card>
        <div>
            <div class="flex items-center space-x-2 mb-3">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-semibold text-white">Notes / Symptômes</h3>
            </div>
            <p class="text-gray-300 whitespace-pre-line">{{ $rendezVous->notes }}</p>
        </div>
    </x-card>
    @endif

    <!-- Paiements -->
    @php
        $paiements = $rendezVous->paiements()->with('facture')->get();
    @endphp
    
    @if($paiements->isNotEmpty())
    <x-card>
        <div class="mb-4">
            <div class="flex items-center space-x-2 mb-4">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
                <h3 class="text-xl font-semibold text-white">Paiements</h3>
            </div>
        </div>

        <div class="space-y-4">
            @foreach($paiements as $paiement)
            <div class="p-4 rounded-xl bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-500/30">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-green-500/20 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Référence</p>
                            <p class="text-white font-mono text-sm">{{ $paiement->reference }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-400">Montant</p>
                        <p class="text-2xl font-bold text-green-400">{{ number_format($paiement->montant, 0, ',', ' ') }} FBU</p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 pt-3 border-t border-green-500/20">
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Mode</p>
                        <span class="px-2 py-1 rounded-lg bg-cyan-500/20 text-cyan-400 text-xs font-medium">
                            {{ ucfirst($paiement->mode) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Statut</p>
                        <span class="px-2 py-1 rounded-lg bg-green-500/20 text-green-400 text-xs font-medium">
                            {{ ucfirst($paiement->statut) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-1">Date</p>
                        <p class="text-white text-xs">{{ $paiement->date_paiement->format('d/m/Y H:i') }}</p>
                    </div>
                </div>

                @if($paiement->facture)
                <div class="mt-3 pt-3 border-t border-green-500/20">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400">Facture</p>
                            <p class="text-white font-mono text-sm">{{ $paiement->facture->numero_facture }}</p>
                        </div>
                        <a 
                            href="{{ route('patient.factures.index') }}" 
                            class="px-3 py-1.5 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20 transition-colors text-xs inline-flex items-center space-x-1"
                        >
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                            </svg>
                            <span>Télécharger</span>
                        </a>
                    </div>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </x-card>
    @else
    <x-card>
        <div class="text-center py-8">
            <div class="w-16 h-16 rounded-full bg-yellow-500/10 flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-gray-400 mb-2">Ce rendez-vous n'a pas encore été payé</p>
            <p class="text-sm text-gray-500">Le paiement sera effectué lors de votre visite à l'hôpital</p>
        </div>
    </x-card>
    @endif

    <!-- Dates de création/modification -->
    <x-card>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 mb-1">Créé le</p>
                <p class="text-white">{{ $rendezVous->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-400 mb-1">Dernière modification</p>
                <p class="text-white">{{ $rendezVous->updated_at->format('d/m/Y à H:i') }}</p>
            </div>
        </div>
    </x-card>
</div>
@endsection
