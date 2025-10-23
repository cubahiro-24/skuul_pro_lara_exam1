@extends('layouts.app')

@section('title', 'Gestion des Paiements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Gestion des Paiements
            </h1>
            <p class="text-gray-400 mt-1">Suivi de toutes les transactions</p>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Total Paiements</p>
                    <h3 class="text-3xl font-bold text-white">{{ $stats['total'] }}</h3>
                    <p class="text-xs text-cyan-400 mt-2">Toutes transactions</p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Réussis</p>
                    <h3 class="text-3xl font-bold text-green-400">{{ $stats['reussi'] }}</h3>
                    <p class="text-xs text-green-400 mt-2">Paiements validés</p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Revenus Total</p>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($stats['montant_total'], 0, ',', ' ') }}</h3>
                    <p class="text-xs text-gray-400 mt-2">FBU</p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Ce Mois</p>
                    <h3 class="text-2xl font-bold text-white">{{ number_format($stats['montant_mois'], 0, ',', ' ') }}</h3>
                    <p class="text-xs text-gray-400 mt-2">FBU</p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(251,191,36,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Filtres -->
    <x-card>
        <form method="GET" action="{{ route('admin.paiements.index') }}" class="space-y-4">
            <!-- Filtres rapides -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('admin.paiements.index') }}" 
                   class="p-4 rounded-xl border-2 text-center transition-all {{ !request()->hasAny(['statut', 'mode', 'date_debut', 'date_fin', 'search']) ? 'bg-cyan-500/20 border-cyan-500' : 'bg-gray-800/50 border-gray-700 hover:border-cyan-500/50' }}">
                    <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-400">Tous</div>
                </a>
                <a href="{{ route('admin.paiements.index', ['statut' => 'reussi']) }}" 
                   class="p-4 rounded-xl border-2 text-center transition-all {{ request('statut') === 'reussi' ? 'bg-green-500/20 border-green-500' : 'bg-gray-800/50 border-gray-700 hover:border-green-500/50' }}">
                    <div class="text-2xl font-bold text-green-400">{{ $stats['reussi'] }}</div>
                    <div class="text-sm text-gray-400">Réussis</div>
                </a>
                <a href="{{ route('admin.paiements.index', ['statut' => 'en_attente']) }}" 
                   class="p-4 rounded-xl border-2 text-center transition-all {{ request('statut') === 'en_attente' ? 'bg-yellow-500/20 border-yellow-500' : 'bg-gray-800/50 border-gray-700 hover:border-yellow-500/50' }}">
                    <div class="text-2xl font-bold text-yellow-400">{{ $stats['en_attente'] }}</div>
                    <div class="text-sm text-gray-400">En attente</div>
                </a>
                <a href="{{ route('admin.paiements.index', ['statut' => 'echoue']) }}" 
                   class="p-4 rounded-xl border-2 text-center transition-all {{ request('statut') === 'echoue' ? 'bg-red-500/20 border-red-500' : 'bg-gray-800/50 border-gray-700 hover:border-red-500/50' }}">
                    <div class="text-2xl font-bold text-red-400">{{ $stats['echoue'] }}</div>
                    <div class="text-sm text-gray-400">Échoués</div>
                </a>
            </div>

            <!-- Filtres avancés -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Référence ou patient..."
                           class="w-full px-4 py-2.5 bg-gray-800 border border-cyan-500/30 rounded-lg text-white focus:ring-2 focus:ring-cyan-500/50 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Mode</label>
                    <select name="mode" class="w-full px-4 py-2.5 bg-gray-800 border border-cyan-500/30 rounded-lg text-white focus:ring-2 focus:ring-cyan-500/50 focus:border-transparent">
                        <option value="">Tous les modes</option>
                        <option value="wallet" {{ request('mode') === 'wallet' ? 'selected' : '' }}>Wallet</option>
                        <option value="mobile_money" {{ request('mode') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                        <option value="carte_bancaire" {{ request('mode') === 'carte_bancaire' ? 'selected' : '' }}>Carte Bancaire</option>
                        <option value="especes" {{ request('mode') === 'especes' ? 'selected' : '' }}>Espèces</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                           class="w-full px-4 py-2.5 bg-gray-800 border border-cyan-500/30 rounded-lg text-white focus:ring-2 focus:ring-cyan-500/50 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                           class="w-full px-4 py-2.5 bg-gray-800 border border-cyan-500/30 rounded-lg text-white focus:ring-2 focus:ring-cyan-500/50 focus:border-transparent">
                </div>
            </div>

            <div class="flex justify-between">
                <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-lg font-medium hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all">
                    🔍 Filtrer
                </button>
                @if(request()->hasAny(['statut', 'mode', 'date_debut', 'date_fin', 'search']))
                <a href="{{ route('admin.paiements.index') }}" class="px-6 py-2.5 bg-gray-700 text-white rounded-lg font-medium hover:bg-gray-600 transition-all">
                    ✖ Réinitialiser
                </a>
                @endif
            </div>
        </form>
    </x-card>

    <!-- Liste des paiements -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Référence</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Patient</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Montant</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Mode</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @forelse($paiements as $paiement)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4">
                            <span class="text-cyan-400 font-mono text-sm">{{ $paiement->reference }}</span>
                        </td>
                        <td class="py-3 px-4">
                            @if($paiement->rendezVous && $paiement->rendezVous->utilisateur)
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($paiement->rendezVous->utilisateur->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $paiement->rendezVous->utilisateur->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $paiement->rendezVous->utilisateur->email }}</p>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($paiement->rendezVous && $paiement->rendezVous->typeService)
                            <span class="text-gray-300">{{ $paiement->rendezVous->typeService->nom }}</span>
                            @else
                            <span class="text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-white font-semibold">{{ number_format($paiement->montant, 0, ',', ' ') }} FBU</span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                @if($paiement->mode === 'wallet')
                                    <span class="text-cyan-400">💳</span>
                                @elseif($paiement->mode === 'mobile_money')
                                    <span class="text-green-400">📱</span>
                                @elseif($paiement->mode === 'carte_bancaire')
                                    <span class="text-blue-400">💳</span>
                                @else
                                    <span class="text-yellow-400">💵</span>
                                @endif
                                <span class="text-gray-300 capitalize">{{ str_replace('_', ' ', $paiement->mode) }}</span>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-300">
                            {{ \Carbon\Carbon::parse($paiement->date_paiement)->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 px-4">
                            @php
                            $statusColors = [
                                'reussi' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                                'echoue' => 'bg-red-500/20 text-red-400 border-red-500/50',
                            ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$paiement->statut] ?? '' }}">
                                {{ ucfirst($paiement->statut) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.paiements.show', $paiement) }}" 
                               class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20 transition-colors inline-flex"
                               title="Voir les détails">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 text-lg">Aucun paiement trouvé</p>
                                <p class="text-gray-500 text-sm mt-1">Les transactions apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($paiements->hasPages())
        <div class="mt-6">
            {{ $paiements->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
