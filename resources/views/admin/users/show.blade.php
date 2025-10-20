@extends('layouts.app')

@section('title', 'Détails Utilisateur')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
            Détails de l'utilisateur
        </h1>
        <a href="{{ route('admin.utilisateurs.index') }}" class="text-cyan-400 hover:text-cyan-300">
            ← Retour à la liste
        </a>
    </div>

    <!-- User Info Card -->
    <x-card>
        <div class="flex items-start space-x-6">
            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center shadow-[0_0_30px_rgba(6,182,212,0.5)]">
                <span class="text-white font-bold text-4xl">{{ substr($utilisateur->name, 0, 1) }}</span>
            </div>
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-white">{{ $utilisateur->name }} {{ $utilisateur->prenom }}</h2>
                <p class="text-gray-400 mt-1">{{ $utilisateur->email }}</p>
                <div class="flex items-center space-x-4 mt-3">
                    @php
                    $roleColors = [
                        'Admin' => 'bg-red-500/20 text-red-400 border-red-500/50',
                        'Medecin' => 'bg-blue-500/20 text-blue-400 border-blue-500/50',
                        'Patient' => 'bg-green-500/20 text-green-400 border-green-500/50',
                        'Secretaire' => 'bg-purple-500/20 text-purple-400 border-purple-500/50',
                        'Caissier' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                    ];
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $roleColors[$utilisateur->role?->nom] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/50' }}">
                        {{ $utilisateur->role?->nom }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-sm font-medium border {{ $utilisateur->statut === 'actif' ? 'bg-green-500/20 text-green-400 border-green-500/50' : 'bg-red-500/20 text-red-400 border-red-500/50' }}">
                        {{ ucfirst($utilisateur->statut) }}
                    </span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a 
                    href="{{ route('admin.utilisateurs.edit', $utilisateur) }}" 
                    class="px-4 py-2 rounded-xl bg-blue-500/20 border border-blue-500/50 text-blue-400 hover:bg-blue-500/30 transition-all"
                >
                    Modifier
                </a>
            </div>
        </div>
    </x-card>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-white">Informations personnelles</h3>
            </x-slot>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-400">Téléphone</p>
                    <p class="text-white font-medium">{{ $utilisateur->telephone ?? 'Non renseigné' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Adresse</p>
                    <p class="text-white font-medium">{{ $utilisateur->adresse ?? 'Non renseignée' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Date d'inscription</p>
                    <p class="text-white font-medium">{{ $utilisateur->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Dernière mise à jour</p>
                    <p class="text-white font-medium">{{ $utilisateur->updated_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </x-card>

        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-white">Statistiques</h3>
            </x-slot>
            <div class="space-y-4">
                @if($utilisateur->role?->nom === 'Patient')
                <div>
                    <p class="text-sm text-gray-400">Total Rendez-vous</p>
                    <p class="text-2xl font-bold text-cyan-400">{{ $utilisateur->rendezVousPatient->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Rendez-vous confirmés</p>
                    <p class="text-2xl font-bold text-green-400">{{ $utilisateur->rendezVousPatient->where('statut', 'confirme')->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Rendez-vous terminés</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $utilisateur->rendezVousPatient->where('statut', 'termine')->count() }}</p>
                </div>
                @elseif($utilisateur->role?->nom === 'Medecin')
                <div>
                    <p class="text-sm text-gray-400">Total Consultations</p>
                    <p class="text-2xl font-bold text-cyan-400">{{ $utilisateur->rendezVousMedecin->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Consultations terminées</p>
                    <p class="text-2xl font-bold text-green-400">{{ $utilisateur->rendezVousMedecin->where('statut', 'termine')->count() }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Patients uniques</p>
                    <p class="text-2xl font-bold text-blue-400">{{ $utilisateur->rendezVousMedecin->unique('utilisateur_id')->count() }}</p>
                </div>
                @else
                <div class="text-center py-8 text-gray-400">
                    Aucune statistique disponible pour ce rôle
                </div>
                @endif
            </div>
        </x-card>
    </div>

    <!-- Recent Activity -->
    @if($utilisateur->role?->nom === 'Patient' && $utilisateur->rendezVousPatient->count() > 0)
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-white">Rendez-vous récents</h3>
        </x-slot>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Médecin</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @foreach($utilisateur->rendezVousPatient->take(5) as $rdv)
                    <tr class="hover:bg-cyan-500/5">
                        <td class="py-3 px-4 text-white">{{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $rdv->typeService->nom }}</td>
                        <td class="py-3 px-4 text-gray-300">Dr. {{ $rdv->medecin->name }}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

    @if($utilisateur->role?->nom === 'Medecin' && $utilisateur->rendezVousMedecin->count() > 0)
    <x-card>
        <x-slot name="header">
            <h3 class="text-lg font-semibold text-white">Consultations récentes</h3>
        </x-slot>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Patient</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @foreach($utilisateur->rendezVousMedecin->take(5) as $rdv)
                    <tr class="hover:bg-cyan-500/5">
                        <td class="py-3 px-4 text-white">{{ \Carbon\Carbon::parse($rdv->date_rdv)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $rdv->utilisateur->name }} {{ $rdv->utilisateur->prenom }}</td>
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
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-card>
    @endif
</div>
@endsection
