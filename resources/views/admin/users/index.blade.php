@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold font-orbitron bg-gradient-to-r from-cyan-400 to-blue-400 bg-clip-text text-transparent">
                Gestion des Utilisateurs
            </h1>
            <p class="text-gray-400 mt-1">Gérez les comptes utilisateurs du système</p>
        </div>
        <a 
            href="{{ route('admin.utilisateurs.create') }}" 
            class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all inline-flex items-center space-x-2"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Nouvel Utilisateur</span>
        </a>
    </div>

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

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach(\App\Models\Role::withCount('users')->get() as $role)
        <x-card class="hover:scale-105 transition-transform">
            <div class="text-center">
                <h3 class="text-2xl font-bold text-cyan-400">{{ $role->users_count }}</h3>
                <p class="text-sm text-gray-400 mt-1">{{ $role->nom }}s</p>
            </div>
        </x-card>
        @endforeach
    </div>

    <!-- Users Table -->
    <x-card>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-cyan-500/20">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Utilisateur</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Email</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Téléphone</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Rôle</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Statut</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-500/10">
                    @forelse($users as $user)
                    <tr class="hover:bg-cyan-500/5 transition-colors">
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-white font-medium">{{ $user->name }} {{ $user->prenom }}</p>
                                    <p class="text-xs text-gray-400">Inscrit {{ $user->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3 px-4 text-gray-300">{{ $user->email }}</td>
                        <td class="py-3 px-4 text-gray-300">{{ $user->telephone ?? 'N/A' }}</td>
                        <td class="py-3 px-4">
                            @php
                            $roleColors = [
                                'Admin' => 'bg-red-500/20 text-red-400 border-red-500/50',
                                'Medecin' => 'bg-blue-500/20 text-blue-400 border-blue-500/50',
                                'Patient' => 'bg-green-500/20 text-green-400 border-green-500/50',
                                'Secretaire' => 'bg-purple-500/20 text-purple-400 border-purple-500/50',
                                'Caissier' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/50',
                            ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $roleColors[$user->role?->nom] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/50' }}">
                                {{ $user->role?->nom ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $user->statut === 'actif' ? 'bg-green-500/20 text-green-400 border-green-500/50' : 'bg-red-500/20 text-red-400 border-red-500/50' }}">
                                {{ ucfirst($user->statut) }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="flex items-center space-x-2">
                                <a 
                                    href="{{ route('admin.utilisateurs.show', $user) }}" 
                                    class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 hover:bg-cyan-500/20 transition-colors"
                                    title="Voir"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                <a 
                                    href="{{ route('admin.utilisateurs.edit', $user) }}" 
                                    class="p-2 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 hover:bg-blue-500/20 transition-colors"
                                    title="Modifier"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                @if($user->id !== auth()->id())
                                <form 
                                    action="{{ route('admin.utilisateurs.destroy', $user) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur?')"
                                    class="inline"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button 
                                        type="submit"
                                        class="p-2 rounded-lg bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500/20 transition-colors"
                                        title="Supprimer"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-400">Aucun utilisateur trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
        @endif
    </x-card>
</div>
@endsection
