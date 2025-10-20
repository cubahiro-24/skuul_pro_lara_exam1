@extends('layouts.app')

@section('title', 'Dashboard Patient')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Espace Patient
            </h1>
            <p class="text-gray-400 mt-1">Bienvenue, {{ auth()->user()->name }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('patient.rendez-vous.create') }}" class="group">
            <x-card class="hover:scale-105 transition-transform cursor-pointer">
                <div class="text-center py-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center mx-auto mb-4 group-hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-shadow">
                        <svg class="w-10 h-10 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Prendre Rendez-vous</h3>
                    <p class="text-gray-400 text-sm mt-2">Réservez une consultation</p>
                </div>
            </x-card>
        </a>

        <a href="{{ route('patient.rendez-vous.index') }}" class="group">
            <x-card class="hover:scale-105 transition-transform cursor-pointer">
                <div class="text-center py-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center mx-auto mb-4 group-hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] transition-shadow">
                        <svg class="w-10 h-10 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Mes Rendez-vous</h3>
                    <p class="text-gray-400 text-sm mt-2">Consultez vos RDV</p>
                </div>
            </x-card>
        </a>

        <a href="{{ route('patient.factures.index') }}" class="group">
            <x-card class="hover:scale-105 transition-transform cursor-pointer">
                <div class="text-center py-6">
                    <div class="w-20 h-20 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center mx-auto mb-4 group-hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-shadow">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white">Mes Factures</h3>
                    <p class="text-gray-400 text-sm mt-2">Téléchargez vos factures</p>
                </div>
            </x-card>
        </a>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">RDV Total</p>
                    <h3 class="text-3xl font-bold text-white">{{ auth()->user()->rendezVousPatient()->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-cyan-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">En attente</p>
                    <h3 class="text-3xl font-bold text-yellow-400">{{ auth()->user()->rendezVousPatient()->where('statut', 'en_attente')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Confirmés</p>
                    <h3 class="text-3xl font-bold text-green-400">{{ auth()->user()->rendezVousPatient()->where('statut', 'confirme')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Terminés</p>
                    <h3 class="text-3xl font-bold text-blue-400">{{ auth()->user()->rendezVousPatient()->where('statut', 'termine')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Prochains Rendez-vous -->
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Prochains Rendez-vous</h3>
                <a href="{{ route('patient.rendez-vous.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300">Voir tout →</a>
            </div>
        </x-slot>

        <div class="space-y-4">
            @forelse(auth()->user()->rendezVousPatient()->with(['typeService.service', 'medecin'])->where('date_rdv', '>=', today())->orderBy('date_rdv')->take(5)->get() as $rdv)
            <div class="p-4 rounded-xl bg-gray-800/50 border border-cyan-500/20 hover:border-cyan-500/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="text-white font-semibold">{{ $rdv->typeService->service->nom }} - {{ $rdv->typeService->nom }}</h4>
                        <p class="text-gray-400 text-sm mt-1">
                            <span class="mr-4">📅 {{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y') }}</span>
                            <span>🕐 {{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}</span>
                        </p>
                        @if($rdv->medecin)
                        <p class="text-gray-500 text-sm">Dr. {{ $rdv->medecin->name }} {{ $rdv->medecin->prenom }}</p>
                        @endif
                    </div>
                    <div>
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
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-gray-400">Aucun rendez-vous à venir</p>
                <a href="{{ route('patient.rendez-vous.create') }}" class="inline-block mt-4">
                    <x-button variant="primary">Prendre Rendez-vous</x-button>
                </a>
            </div>
            @endforelse
        </div>
    </x-card>
</div>
@endsection
