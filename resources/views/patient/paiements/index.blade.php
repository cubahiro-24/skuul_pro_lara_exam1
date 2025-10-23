@extends('layouts.app')

@section('title', 'Mes Paiements')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Mes Paiements
            </h1>
            <p class="text-gray-400 mt-1">Historique de vos paiements</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Total Payé</p>
                    <h3 class="text-3xl font-bold text-white">
                        {{ number_format($paiements->sum('montant'), 0, ',', ' ') }} <span class="text-lg">FBU</span>
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Nombre de Paiements</p>
                    <h3 class="text-3xl font-bold text-white">{{ $paiements->total() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-cyan-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Dernier Paiement</p>
                    <h3 class="text-lg font-bold text-white">
                        @if($paiements->count() > 0)
                            {{ $paiements->first()->date_paiement->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-purple-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Payments List -->
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-white">Historique des Paiements</h3>
        </x-slot>

        @if($paiements->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-cyan-500/20">
                    <tr>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-300">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-300">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-300">Médecin</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-300">Montant</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-300">Méthode</th>
                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-300">N° Facture</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($paiements as $paiement)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4 text-sm text-gray-300">
                            {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                        </td>
                        <td class="py-3 px-4">
                            <div>
                                <p class="text-sm font-medium text-white">
                                    {{ $paiement->rendezVous->typeService->service->nom ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $paiement->rendezVous->typeService->nom ?? '-' }}
                                </p>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-300">
                            {{ $paiement->rendezVous->medecin->name ?? '-' }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="text-sm font-semibold text-green-400">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} FBU
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-1 rounded-lg text-xs font-medium bg-blue-500/20 text-blue-400">
                                {{ ucfirst($paiement->mode) }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-300">
                            {{ $paiement->reference ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $paiements->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
            <p class="text-gray-400">Aucun paiement enregistré</p>
        </div>
        @endif
    </x-card>
</div>
@endsection
