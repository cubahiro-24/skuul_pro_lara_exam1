@extends('layouts.app')

@section('title', 'Gestion des Services')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-cyan-400 to-blue-400">Gestion des Services</h1>
            <p class="text-gray-400 mt-1">Créez et gérez les services médicaux et leurs types</p>
        </div>
        <div>
            <a href="{{ route('admin.services.create') }}" class="px-4 py-2 rounded-xl bg-cyan-600 text-white font-semibold hover:brightness-110">Nouveau Service</a>
        </div>
    </div>

    <x-card>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($services as $service)
            <div class="p-4 rounded-lg border border-cyan-500/10 bg-gradient-to-br from-white/2 to-white/1">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-white">{{ $service->nom }}</h3>
                        <p class="text-sm text-gray-400 mt-1">{{ Str::limit($service->description, 100) }}</p>
                        <div class="mt-3 flex items-center space-x-2">
                            <span class="px-2 py-1 rounded-full text-xs bg-blue-500/10 text-blue-300">{{ $service->type_services_count ?? $service->typeServices->count() }} types</span>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ route('admin.services.show', $service) }}" class="text-cyan-400 hover:text-cyan-300" title="Voir">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.services.edit', $service) }}" class="text-green-400 hover:text-green-300" title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h6M11 9h6M11 13h6M5 7h.01M5 11h.01M5 15h.01M9 21h6"/></svg>
                        </a>
                        <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Supprimer ce service ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300" title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center text-gray-400 py-10">Aucun service trouvé</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </x-card>
</div>
@endsection
