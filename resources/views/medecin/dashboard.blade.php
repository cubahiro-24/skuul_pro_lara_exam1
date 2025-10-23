@extends('layouts.app')

@section('title', 'Dashboard Médecin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Espace Médecin
            </h1>
            <p class="text-gray-400 mt-1">Bienvenue, Dr. {{ auth()->user()->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-400">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            <p class="text-2xl font-bold text-cyan-400">{{ now()->format('H:i') }}</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Aujourd'hui</p>
                    <h3 class="text-3xl font-bold text-white">{{ auth()->user()->rendezVousMedecin()->whereDate('date_rdv', today())->count() }}</h3>
                    <p class="text-xs text-cyan-400 mt-2">
                        {{ auth()->user()->rendezVousMedecin()->whereDate('date_rdv', today())->where('statut', 'confirme')->count() }} confirmés
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Cette semaine</p>
                    <h3 class="text-3xl font-bold text-white">{{ auth()->user()->rendezVousMedecin()->whereBetween('date_rdv', [now()->startOfWeek(), now()->endOfWeek()])->count() }}</h3>
                    <p class="text-xs text-green-400 mt-2">
                        Planning de la semaine
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Patients Total</p>
                    <h3 class="text-3xl font-bold text-white">{{ auth()->user()->rendezVousMedecin()->distinct('utilisateur_id')->count('utilisateur_id') }}</h3>
                    <p class="text-xs text-blue-400 mt-2">
                        Patients suivis
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="group hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">En attente</p>
                    <h3 class="text-3xl font-bold text-yellow-400">{{ auth()->user()->rendezVousMedecin()->where('statut', 'en_attente')->count() }}</h3>
                    <p class="text-xs text-yellow-400 mt-2">
                        À confirmer
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-yellow-500/20 to-orange-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(251,191,36,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Rendez-vous d'aujourd'hui -->
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Rendez-vous d'Aujourd'hui</h3>
                <span class="text-sm text-gray-400">{{ now()->format('d/m/Y') }}</span>
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Heure</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Patient</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @forelse(auth()->user()->rendezVousMedecin()->with(['utilisateur', 'typeService'])->whereDate('date_rdv', today())->orderBy('heure_rdv')->get() as $rdv)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4 text-white font-medium">{{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}</td>
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
                        <td class="py-3 px-4 text-gray-300">{{ $rdv->typeService->nom }}</td>
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
                                <a href="{{ route('medecin.rendez-vous.show', $rdv) }}" class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                @if($rdv->statut === 'en_attente')
                                <form method="POST" action="{{ route('medecin.rendez-vous.update-status', $rdv) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="statut" value="confirme">
                                    <button type="submit" class="p-2 rounded-lg bg-green-500/10 border border-green-500/30 text-green-400 hover:bg-green-500/20 transition-colors" onclick="return confirm('Confirmer ce rendez-vous ?')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-400">Aucun rendez-vous aujourd'hui</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <!-- Rendez-vous à venir -->
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Prochains Rendez-vous</h3>
                <a href="{{ route('medecin.rendez-vous.index') }}" class="text-sm text-cyan-400 hover:text-cyan-300">Voir tout →</a>
            </div>
        </x-slot>

        <div class="space-y-3">
            @foreach(auth()->user()->rendezVousMedecin()->with(['utilisateur', 'typeService'])->where('date_rdv', '>', today())->orderBy('date_rdv')->orderBy('heure_rdv')->take(5)->get() as $rdv)
            <div class="p-4 rounded-xl bg-gray-800/50 border border-cyan-500/20 hover:border-cyan-500/50 transition-colors">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                                <span class="text-white font-bold text-xs">{{ substr($rdv->utilisateur->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-white font-medium">{{ $rdv->utilisateur->name }} {{ $rdv->utilisateur->prenom }}</p>
                                <p class="text-xs text-gray-400">{{ $rdv->typeService->nom }}</p>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm">
                            <span class="mr-4">📅 {{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y') }}</span>
                            <span>🕐 {{ \Carbon\Carbon::parse($rdv->heure_rdv)->format('H:i') }}</span>
                        </p>
                    </div>
                    <div>
                        @php
                        $statusColors = [
                            'en_attente' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                            'confirme' => 'bg-green-500/20 text-green-400 border-green-500/50',
                        ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColors[$rdv->statut] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $rdv->statut)) }}
                        </span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </x-card>
</div>
@endsection
