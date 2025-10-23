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
            <p class="text-gray-400 mt-1">Gérez vos consultations médicales</p>
        </div>
        <a 
            href="{{ route('patient.rendez-vous.create') }}" 
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all inline-flex items-center space-x-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouveau Rendez-vous</span>
        </a>
    </div>

    <!-- Filters -->
    <x-card>
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-400">Filtrer par statut:</span>
                <select class="px-4 py-2 rounded-lg bg-gray-800/50 border border-cyan-500/30 text-white text-sm focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50">
                    <option value="">Tous</option>
                    <option value="en_attente">En attente</option>
                    <option value="confirme">Confirmé</option>
                    <option value="termine">Terminé</option>
                    <option value="annule">Annulé</option>
                </select>
            </div>
        </div>
    </x-card>

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

    <!-- Appointments List -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date & Heure</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Médecin</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Paiement</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @forelse($rendezVous as $rdv)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4">
                            <div class="text-white font-medium">{{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y') }}</div>
                            <div class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-white">{{ $rdv->typeService->nom }}</div>
                            <div class="text-sm text-gray-400">{{ $rdv->typeService->service->nom }}</div>
                            <div class="text-sm text-cyan-400 font-semibold">{{ number_format($rdv->typeService->prix, 0, ',', ' ') }} FBU</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($rdv->medecin->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">Dr. {{ $rdv->medecin->name }}</p>
                                    <p class="text-xs text-gray-400">{{ $rdv->medecin->telephone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            @if($rdv->paiements->isNotEmpty())
                                @php
                                    $paiement = $rdv->paiements->first();
                                    $paiementColors = [
                                        'reussi' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                        'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                                        'echoue' => 'bg-red-500/20 text-red-400 border-red-500/50',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium border {{ $paiementColors[$paiement->statut] ?? '' }}">
                                    {{ ucfirst($paiement->statut) }}
                                </span>
                                <div class="text-xs text-gray-400 mt-1">{{ number_format($paiement->montant, 0, ',', ' ') }} FBU</div>
                                @if($paiement->mode === 'wallet')
                                <div class="text-xs text-yellow-400 flex items-center space-x-1 mt-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <span>Wallet</span>
                                </div>
                                @endif
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-medium border bg-gray-500/20 text-gray-400 border-gray-500/50">
                                    Non payé
                                </span>
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
                                <a 
                                    href="{{ route('patient.rendez-vous.show', $rdv) }}" 
                                    class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20 transition-colors"
                                    title="Voir détails"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if(in_array($rdv->statut, ['en_attente', 'confirme']))
                                <form 
                                    action="{{ route('patient.rendez-vous.destroy', $rdv) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous?')"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="p-2 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 transition-colors"
                                        title="Annuler"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-4">
                                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <p class="text-gray-400">Aucun rendez-vous trouvé</p>
                                <a 
                                    href="{{ route('patient.rendez-vous.create') }}" 
                                    class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all"
                                >
                                    Prendre un rendez-vous
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rendezVous->hasPages())
        <div class="mt-6">
            {{ $rendezVous->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
