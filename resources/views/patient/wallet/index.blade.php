@extends('layouts.app')

@section('title', 'Mon Portefeuille')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Mon Portefeuille Virtuel
            </h1>
            <p class="text-gray-400 mt-1">Gérez votre portefeuille en Francs Burundais (FBU)</p>
        </div>
        <a 
            href="{{ route('patient.wallet.recharger') }}" 
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-all inline-flex items-center space-x-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Recharger</span>
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/50 text-green-400 flex items-center space-x-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="p-4 rounded-xl bg-red-500/10 border border-red-500/50 text-red-400 flex items-center space-x-3">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <!-- Wallet Balance Card -->
    <x-card class="relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 1s;"></div>
        </div>

        <div class="relative">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Solde Disponible</p>
                        <p class="text-xs text-gray-500">Devise: {{ $wallet->devise }}</p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-lg text-xs font-medium {{ $wallet->is_active ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                    {{ $wallet->is_active ? 'Actif' : 'Inactif' }}
                </span>
            </div>

            <div class="text-center py-8">
                <h2 class="text-5xl font-bold font-orbitron text-white mb-2">
                    {{ $wallet->solde_formate }}
                </h2>
                <p class="text-gray-400 text-sm">Votre solde actuel</p>
            </div>

            <div class="grid grid-cols-3 gap-4 pt-6 border-t border-cyan-500/20">
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Total Rechargé</p>
                    <p class="text-lg font-semibold text-green-400">
                        {{ number_format($stats['total_recharge'], 0, ',', ' ') }} FBU
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Total Dépensé</p>
                    <p class="text-lg font-semibold text-red-400">
                        {{ number_format($stats['total_depense'], 0, ',', ' ') }} FBU
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-xs text-gray-400 mb-1">Transactions</p>
                    <p class="text-lg font-semibold text-cyan-400">
                        {{ $stats['total_transactions'] }}
                    </p>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Recent Transactions -->
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Transactions Récentes</h3>
                <a href="{{ route('patient.wallet.transactions') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
                    Voir tout →
                </a>
            </div>
        </x-slot>

        @if($transactions->count() > 0)
        <div class="space-y-3">
            @foreach($transactions as $transaction)
            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-800/30 border border-gray-700/50 hover:border-cyan-500/30 transition-all">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center 
                        {{ $transaction->type === 'rechargement' ? 'bg-green-500/20' : '' }}
                        {{ $transaction->type === 'paiement' ? 'bg-red-500/20' : '' }}
                        {{ $transaction->type === 'remboursement' ? 'bg-blue-500/20' : '' }}">
                        @if($transaction->type === 'rechargement')
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        @elseif($transaction->type === 'paiement')
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                        </svg>
                        @else
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">{{ $transaction->type_label }}</p>
                        <p class="text-xs text-gray-400">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                        @if($transaction->description)
                        <p class="text-xs text-gray-500">{{ $transaction->description }}</p>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold {{ $transaction->type === 'rechargement' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $transaction->type === 'rechargement' ? '+' : '-' }}{{ $transaction->montant_formate }}
                    </p>
                    <p class="text-xs text-gray-400">Réf: {{ substr($transaction->reference, -8) }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transactions->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p class="text-gray-400">Aucune transaction pour le moment</p>
            <a href="{{ route('patient.wallet.recharger') }}" class="inline-block mt-4">
                <x-button variant="primary">Recharger votre portefeuille</x-button>
            </a>
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
                <h4 class="text-white font-semibold mb-2">Comment ça marche?</h4>
                <ul class="text-sm text-gray-400 space-y-1">
                    <li>• Rechargez votre portefeuille via Mobile Money, Carte bancaire ou Espèces</li>
                    <li>• Utilisez votre solde pour payer vos consultations directement</li>
                    <li>• Consultez l'historique de toutes vos transactions</li>
                    <li>• Recevez des notifications pour chaque opération</li>
                    <li>• Montant minimum de rechargement: 1,000 FBU</li>
                    <li>• Montant maximum de rechargement: 5,000,000 FBU</li>
                </ul>
            </div>
        </div>
    </x-card>
</div>
@endsection
