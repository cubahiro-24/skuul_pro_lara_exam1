@extends('layouts.app')

@section('title', 'Mes Rendez-vous')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Mes Rendez-vous
            </h1>
            <p class="text-gray-400 mt-1">Gestion de vos consultations</p>
        </div>
    </div>

    <!-- Filtres -->
    <x-card>
        <form method="GET" action="{{ route('medecin.rendez-vous.index') }}" class="space-y-4">
            <!-- Statistiques rapides -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <a href="{{ route('medecin.rendez-vous.index') }}" 
                   class="p-4 rounded-xl border-2 transition-all {{ !request('statut') ? 'bg-cyan-500/20 border-cyan-500' : 'bg-gray-800/50 border-gray-700 hover:border-cyan-500/50' }}">
                    <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-sm text-gray-400">Total</div>
                </a>
                <a href="{{ route('medecin.rendez-vous.index', ['statut' => 'en_attente']) }}" 
                   class="p-4 rounded-xl border-2 transition-all {{ request('statut') === 'en_attente' ? 'bg-yellow-500/20 border-yellow-500' : 'bg-gray-800/50 border-gray-700 hover:border-yellow-500/50' }}">
                    <div class="text-2xl font-bold text-yellow-400">{{ $stats['en_attente'] }}</div>
                    <div class="text-sm text-gray-400">En attente</div>
                </a>
                <a href="{{ route('medecin.rendez-vous.index', ['statut' => 'confirme']) }}" 
                   class="p-4 rounded-xl border-2 transition-all {{ request('statut') === 'confirme' ? 'bg-green-500/20 border-green-500' : 'bg-gray-800/50 border-gray-700 hover:border-green-500/50' }}">
                    <div class="text-2xl font-bold text-green-400">{{ $stats['confirme'] }}</div>
                    <div class="text-sm text-gray-400">Confirmés</div>
                </a>
                <a href="{{ route('medecin.rendez-vous.index', ['statut' => 'termine']) }}" 
                   class="p-4 rounded-xl border-2 transition-all {{ request('statut') === 'termine' ? 'bg-blue-500/20 border-blue-500' : 'bg-gray-800/50 border-gray-700 hover:border-blue-500/50' }}">
                    <div class="text-2xl font-bold text-blue-400">{{ $stats['termine'] }}</div>
                    <div class="text-sm text-gray-400">Terminés</div>
                </a>
                <a href="{{ route('medecin.rendez-vous.index', ['statut' => 'annule']) }}" 
                   class="p-4 rounded-xl border-2 transition-all {{ request('statut') === 'annule' ? 'bg-red-500/20 border-red-500' : 'bg-gray-800/50 border-gray-700 hover:border-red-500/50' }}">
                    <div class="text-2xl font-bold text-red-400">{{ $stats['annule'] }}</div>
                    <div class="text-sm text-gray-400">Annulés</div>
                </a>
            </div>

            <!-- Filtres avancés -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date de début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut') }}"
                           class="w-full px-4 py-2.5 bg-gray-800 border border-cyan-500/30 rounded-lg text-white focus:ring-2 focus:ring-cyan-500/50 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date de fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin') }}"
                           class="w-full px-4 py-2.5 bg-gray-800 border border-cyan-500/30 rounded-lg text-white focus:ring-2 focus:ring-cyan-500/50 focus:border-transparent">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-lg font-medium hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all">
                        🔍 Filtrer
                    </button>
                </div>
            </div>

            @if(request()->hasAny(['statut', 'date_debut', 'date_fin']))
            <div class="flex justify-end">
                <a href="{{ route('medecin.rendez-vous.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300">
                    ✖ Réinitialiser les filtres
                </a>
            </div>
            @endif
        </form>
    </x-card>

    <!-- Liste des rendez-vous -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date & Heure</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Patient</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Paiement</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @forelse($rendezVous as $rdv)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4">
                            <div>
                                <p class="text-white font-medium">{{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-400">🕐 {{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}</p>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($rdv->utilisateur->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $rdv->utilisateur->name }} {{ $rdv->utilisateur->prenom }}</p>
                                    <p class="text-xs text-gray-400">{{ $rdv->utilisateur->telephone }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <div>
                                <p class="text-white">{{ $rdv->typeService->nom }}</p>
                                <p class="text-xs text-gray-400">{{ number_format($rdv->typeService->prix, 0, ',', ' ') }} FBU</p>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($rdv->paiements->isEmpty())
                                <span class="px-3 py-1 rounded-full text-xs font-medium border bg-gray-500/20 text-gray-400 border-gray-500/50">
                                    Non payé
                                </span>
                            @else
                                @php
                                    $paiement = $rdv->paiements->first();
                                    $paiementColors = [
                                        'reussi' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                        'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                                        'echoue' => 'bg-red-500/20 text-red-400 border-red-500/50',
                                    ];
                                @endphp
                                <div class="flex items-center space-x-2">
                                    @if($paiement->mode === 'wallet')
                                        <span class="text-cyan-400">💳</span>
                                    @endif
                                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $paiementColors[$paiement->statut] ?? '' }}">
                                        {{ ucfirst($paiement->statut) }}
                                    </span>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @php
                            $statusColors = [
                                'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                                'confirme' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                'annule' => 'bg-red-500/20 text-red-400 border-red-500/50',
                                'termine' => 'bg-blue-500/20 text-blue-400 border-blue-500/50',
                            ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$rdv->statut] ?? '' }}">
                                {{ ucfirst(str_replace('_', ' ', $rdv->statut)) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <!-- Voir détails -->
                                <a href="{{ route('medecin.rendez-vous.show', $rdv) }}" 
                                   class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20 transition-colors"
                                   title="Voir les détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>

                                <!-- Confirmer (si en attente) -->
                                @if($rdv->statut === 'en_attente')
                                <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rdv) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="statut" value="confirme">
                                    <button type="submit" 
                                            class="p-2 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 hover:bg-green-500/20 transition-colors"
                                            title="Confirmer le rendez-vous"
                                            onclick="return confirm('Confirmer ce rendez-vous ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                                @endif

                                <!-- Terminer (si confirmé) -->
                                @if($rdv->statut === 'confirme')
                                <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rdv) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="statut" value="termine">
                                    <button type="submit" 
                                            class="p-2 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 hover:bg-blue-500/20 transition-colors"
                                            title="Marquer comme terminé"
                                            onclick="return confirm('Marquer ce rendez-vous comme terminé ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center mb-4">
                                    <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-gray-400 text-lg">Aucun rendez-vous trouvé</p>
                                <p class="text-gray-500 text-sm mt-1">Les rendez-vous assignés apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($rendezVous->hasPages())
        <div class="mt-6">
            {{ $rendezVous->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
