@extends('layouts.app')

@section('title', 'Détails du Service')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-white">{{ $service->nom }}</h1>
            <p class="text-gray-400">{{ $service->description }}</p>
        </div>
        <div>
            <a href="{{ route('admin.services.edit', $service) }}" class="px-3 py-2 rounded-lg bg-blue-600 text-white">Modifier</a>
        </div>
    </div>

    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-semibold text-white">Détails</h3>
                <p class="text-gray-400 mt-2">Icône: <span class="text-white">{{ $service->icone ?? '—' }}</span></p>
                <p class="text-gray-400 mt-2">Créé le: <span class="text-white">{{ $service->created_at->format('d/m/Y') }}</span></p>
                <p class="text-gray-400 mt-2">Mise à jour: <span class="text-white">{{ $service->updated_at->format('d/m/Y') }}</span></p>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">Types de services</h3>
                @if($service->typeServices->isEmpty())
                <p class="text-gray-400 mt-2">Aucun type de service pour ce service.</p>
                @else
                <ul class="space-y-3 mt-3">
                    @foreach($service->typeServices as $type)
                    <li class="p-3 rounded-md bg-gradient-to-br from-white/2 to-white/1 border border-cyan-500/10">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-white font-semibold">{{ $type->nom }}</div>
                                <div class="text-sm text-gray-400">{{ Str::limit($type->description, 80) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-white font-bold">{{ number_format($type->prix, 2, ',', ' ') }} F</div>
                                <div class="text-sm text-gray-400">{{ $type->duree_minutes }} min</div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </x-card>
</div>
@endsection
