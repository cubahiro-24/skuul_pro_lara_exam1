@extends('layouts.app')

@section('title', 'Détails Rendez-vous')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Détails du Rendez-vous</h1>
            <p class="text-gray-400">#{{ $rendezVous->id }}</p>
        </div>
        <div>
            <a href="{{ route('admin.rendez-vous.index') }}" class="text-cyan-400">Retour</a>
        </div>
    </div>

    <x-card>
        <div class="space-y-4">
            <p><strong>Patient:</strong> {{ $rendezVous->utilisateur?->name ?? '-' }}</p>
            <p><strong>Service:</strong> {{ $rendezVous->typeService?->service?->nom ?? $rendezVous->typeService?->nom ?? '-' }}</p>
            <p><strong>Date:</strong> {{ optional($rendezVous->date_rdv)->format('Y-m-d') }}</p>
            <p><strong>Heure:</strong> {{ optional($rendezVous->heure_rdv)->format('H:i') }}</p>
            <p><strong>Statut:</strong> {{ $rendezVous->statut }}</p>
            <p><strong>Notes:</strong> {{ $rendezVous->notes }}</p>
        </div>

        <hr class="my-4" />

        <form action="{{ route('admin.rendez-vous.update', $rendezVous) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm text-gray-300">Statut</label>
                <select name="statut" class="w-full mt-1 p-2 rounded bg-slate-800">
                    @foreach(['en_attente','confirme','termine','annule'] as $s)
                        <option value="{{ $s }}" @selected($rendezVous->statut === $s)>{{ $s }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-300">Médecin</label>
                <select name="medecin_id" class="w-full mt-1 p-2 rounded bg-slate-800">
                    <option value="">-- Aucun --</option>
                    @foreach($medecins as $m)
                        <option value="{{ $m->id }}" @selected($rendezVous->medecin_id === $m->id)>{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-300">Notes</label>
                <textarea name="notes" class="w-full mt-1 p-2 rounded bg-slate-800" rows="4">{{ old('notes', $rendezVous->notes) }}</textarea>
            </div>

            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 bg-cyan-600 text-white rounded">Enregistrer</button>
                <form action="{{ route('admin.rendez-vous.destroy', $rendezVous) }}" method="POST" onsubmit="return confirm('Supprimer ce rendez-vous ?')">
                    @csrf
                    @method('DELETE')
                    <button class="px-4 py-2 bg-red-600 text-white rounded">Supprimer</button>
                </form>
            </div>
        </form>
    </x-card>
</div>
@endsection
