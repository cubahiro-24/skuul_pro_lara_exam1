@extends('layouts.app')

@section('title', 'Mes Factures')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Mes Factures
            </h1>
            <p class="text-gray-400 mt-1">Téléchargez et consultez vos factures</p>
        </div>
    </div>

    <!-- Factures List -->
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-white">Liste des Factures</h3>
        </x-slot>

        @if($factures->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($factures as $facture)
            <div class="bg-gradient-to-br from-gray-800/50 to-gray-900/50 rounded-xl p-6 border border-cyan-500/20 hover:border-cyan-500/40 transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <span class="px-3 py-1 rounded-lg text-xs font-medium bg-green-500/20 text-green-400">
                        Payée
                    </span>
                </div>

                <div class="space-y-2">
                    <div>
                        <p class="text-xs text-gray-400">Référence</p>
                        <p class="text-sm font-semibold text-white">{{ $facture->reference }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Service</p>
                        <p class="text-sm text-white">{{ $facture->rendezVous->typeService->service->nom ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $facture->rendezVous->typeService->nom ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Médecin</p>
                        <p class="text-sm text-white">{{ $facture->rendezVous->medecin->name ?? '-' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400">Date de Paiement</p>
                        <p class="text-sm text-white">{{ $facture->date_paiement->format('d/m/Y') }}</p>
                    </div>

                    <div class="pt-3 border-t border-gray-700">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-400">Montant</span>
                            <span class="text-lg font-bold text-green-400">
                                {{ number_format($facture->montant, 0, ',', ' ') }} FBU
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-700">
                    <button class="w-full px-4 py-2 rounded-lg bg-cyan-500/20 border border-cyan-500/50 text-cyan-400 hover:bg-cyan-500/30 transition-all flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span>Télécharger PDF</span>
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $factures->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <p class="text-gray-400 mb-2">Aucune facture disponible</p>
            <p class="text-sm text-gray-500">Les factures apparaîtront ici après vos paiements</p>
        </div>
        @endif
    </x-card>

    <!-- Info Card -->
    <x-card>
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-2">À propos des factures</h4>
                <ul class="text-sm text-gray-400 space-y-1">
                    <li>• Les factures sont générées automatiquement après chaque paiement</li>
                    <li>• Vous pouvez télécharger vos factures au format PDF à tout moment</li>
                    <li>• Conservez vos factures pour vos remboursements d'assurance</li>
                    <li>• Pour toute question, contactez notre service comptabilité</li>
                </ul>
            </div>
        </div>
    </x-card>
</div>
@endsection
