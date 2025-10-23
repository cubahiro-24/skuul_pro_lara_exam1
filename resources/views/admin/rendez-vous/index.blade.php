@extends('layouts.app')

@section('title', 'Rendez-vous')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">Rendez-vous</h1>
            <p class="text-gray-400">Liste des rendez-vous</p>
        </div>
    </div>

    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr class="text-left text-sm text-gray-400">
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Service</th>
                        <th class="px-4 py-3">Date</th>
                        <th class="px-4 py-3">Heure</th>
                        <th class="px-4 py-3">Médecin</th>
                        <th class="px-4 py-3">Statut</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @forelse($rendezVous as $rdv)
                    <tr>
                        <td class="px-4 py-3">{{ $rdv->utilisateur->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $rdv->typeService?->service->nom ?? $rdv->typeService?->nom ?? '-' }}</td>
                        <td class="px-4 py-3">{{ optional($rdv->date_rdv)->format('Y-m-d') }}</td>
                        <td class="px-4 py-3">{{ optional($rdv->heure_rdv)->format('H:i') }}</td>
                        <td class="px-4 py-3">{{ $rdv->medecin?->name ?? '-' }}</td>
                        <td class="px-4 py-3">{{ $rdv->statut }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.rendez-vous.show', $rdv) }}" class="text-cyan-400">Voir</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-gray-400">Aucun rendez-vous trouvé</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $rendezVous->links() }}
        </div>
    </x-card>
</div>
@endsection
