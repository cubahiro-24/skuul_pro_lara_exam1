@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Dashboard Administrateur
            </h1>
            <p class="text-gray-400 mt-1">Bienvenue, {{ auth()->user()->name }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-gray-400">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
            <p class="text-2xl font-bold text-cyan-400">{{ now()->format('H:i') }}</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Rendez-vous -->
        <x-card class="group hover:scale-105 transition-transform duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Total Rendez-vous</p>
                    <h3 class="text-3xl font-bold text-white">{{ \App\Models\RendezVous::count() }}</h3>
                    <p class="text-xs text-green-400 mt-2">
                        <span class="inline-block mr-1">↑</span> +12% ce mois
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-cyan-500/20 to-blue-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Total Patients -->
        <x-card class="group hover:scale-105 transition-transform duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Total Patients</p>
                    <h3 class="text-3xl font-bold text-white">{{ \App\Models\User::whereHas('role', fn($q) => $q->where('nom', 'Patient'))->count() }}</h3>
                    <p class="text-xs text-green-400 mt-2">
                        <span class="inline-block mr-1">↑</span> +8% ce mois
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-purple-500/20 to-pink-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(168,85,247,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Revenus du mois -->
        <x-card class="group hover:scale-105 transition-transform duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">Revenus du Mois</p>
                    <h3 class="text-3xl font-bold text-white">{{ number_format(\App\Models\Paiement::where('statut', 'reussi')->whereMonth('date_paiement', now()->month)->whereYear('date_paiement', now()->year)->sum('montant'), 0, ',', ' ') }} FBU</h3>
                    <p class="text-xs text-green-400 mt-2">
                        <span class="inline-block mr-1">↑</span> Paiements validés
                    </p>
                </div>
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-500/20 to-emerald-500/20 flex items-center justify-center group-hover:shadow-[0_0_30px_rgba(34,197,94,0.5)] transition-shadow">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </x-card>

        <!-- Rendez-vous Aujourd'hui -->
        <x-card class="group hover:scale-105 transition-transform duration-200 cursor-pointer">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-400 mb-1">RDV Aujourd'hui</p>
                    <h3 class="text-3xl font-bold text-white">{{ \App\Models\RendezVous::whereDate('date_rdv', today())->count() }}</h3>
                    <p class="text-xs text-cyan-400 mt-2">
                        {{ \App\Models\RendezVous::whereDate('date_rdv', today())->where('statut', 'confirme')->count() }} confirmés
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

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Chart -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-white">Évolution des Revenus</h3>
            </x-slot>
            <div id="revenueChart" class="h-80"></div>
        </x-card>

        <!-- Appointments Chart -->
        <x-card>
            <x-slot name="header">
                <h3 class="text-lg font-semibold text-white">Rendez-vous par Statut</h3>
            </x-slot>
            <div id="appointmentsChart" class="h-80"></div>
        </x-card>
    </div>

    <!-- Recent Appointments Table -->
    <x-card>
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Rendez-vous Récents</h3>
                <a href="/admin/rendez-vous" class="text-sm text-cyan-400 hover:text-cyan-300">Voir tout →</a>
            </div>
        </x-slot>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Patient</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Service</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Heure</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @forelse(\App\Models\RendezVous::with(['utilisateur', 'typeService'])->latest()->take(5)->get() as $rdv)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($rdv->utilisateur->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $rdv->utilisateur->name }} {{ $rdv->utilisateur->prenom }}</p>
                                    <p class="text-xs text-gray-400">{{ $rdv->utilisateur->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-300">{{ $rdv->typeService->nom }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $rdv->date_rdv->format('d/m/Y') }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $rdv->heure_rdv->format('H:i') }}</td>
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
                            <button class="text-cyan-400 hover:text-cyan-300 mr-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">Aucun rendez-vous récent</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueOptions = {
        series: [{
            name: 'Revenus',
            data: [30, 40, 35, 50, 49, 60, 70, 91, 125]
        }],
        chart: {
            type: 'area',
            height: 320,
            background: 'transparent',
            toolbar: { show: false }
        },
        colors: ['#06b6d4'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
            }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
            categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep'],
            labels: { style: { colors: '#9ca3af' } }
        },
        yaxis: {
            labels: { style: { colors: '#9ca3af' } }
        },
        grid: {
            borderColor: 'rgba(6, 182, 212, 0.1)',
        },
        theme: { mode: 'dark' }
    };
    new ApexCharts(document.querySelector("#revenueChart"), revenueOptions).render();

    // Appointments Chart
    const appointmentsOptions = {
        series: [44, 55, 13, 33],
        chart: {
            type: 'donut',
            height: 320,
            background: 'transparent',
        },
        labels: ['Confirmé', 'En attente', 'Annulé', 'Terminé'],
        colors: ['#10b981', '#f59e0b', '#ef4444', '#06b6d4'],
        legend: {
            position: 'bottom',
            labels: { colors: '#9ca3af' }
        },
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            label: 'Total',
                            color: '#fff'
                        }
                    }
                }
            }
        },
        dataLabels: { enabled: false },
        theme: { mode: 'dark' }
    };
    new ApexCharts(document.querySelector("#appointmentsChart"), appointmentsOptions).render();
});
</script>
@endpush
