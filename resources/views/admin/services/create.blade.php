@extends('layouts.app')

@section('title', 'Créer Service')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Créer un nouveau service</h1>
            <p class="text-gray-400">Ajoutez un service et des types associés</p>
        </div>
        <a href="{{ route('admin.services.index') }}" class="text-cyan-400">← Retour</a>
    </div>

    <x-card>
        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-6" x-data="serviceForm()">
            @csrf
            <div>
                <label class="text-sm text-gray-400">Nom</label>
                <input type="text" name="nom" value="{{ old('nom') }}" class="mt-1 w-full rounded-lg bg-transparent border border-cyan-500/10 px-4 py-2 text-white" required>
                @error('nom') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-sm text-gray-400">Description</label>
                <textarea name="description" rows="4" class="mt-1 w-full rounded-lg bg-transparent border border-cyan-500/10 px-4 py-2 text-white">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            <!-- TypeServices (Subservices) -->
            <div>
                <div class="flex items-center justify-between">
                    <label class="text-sm text-gray-400">Types de services</label>
                    <button type="button" @click="addType()" class="px-3 py-1 bg-cyan-600 rounded text-white text-sm">+ Ajouter un type</button>
                </div>
                <template x-for="(ts, index) in types" :key="index">
                    <div class="mt-3 p-3 rounded-lg border border-cyan-500/10 bg-gradient-to-br from-white/2 to-white/1">
                        <div class="flex items-start justify-between">
                            <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <input type="hidden" :name="`type_services[${index}][id]`" x-model="ts.id">
                                    <input type="text" :name="`type_services[${index}][nom]`" x-model="ts.nom" placeholder="Nom" class="w-full rounded bg-transparent border border-cyan-500/10 px-3 py-2 text-white" required>
                                </div>
                                <div>
                                    <input type="text" :name="`type_services[${index}][prix]`" x-model="ts.prix" placeholder="Prix" class="w-full rounded bg-transparent border border-cyan-500/10 px-3 py-2 text-white">
                                </div>
                                <div>
                                    <input type="number" :name="`type_services[${index}][duree_minutes]`" x-model="ts.duree_minutes" placeholder="Durée (min)" class="w-full rounded bg-transparent border border-cyan-500/10 px-3 py-2 text-white">
                                </div>
                                <div class="md:col-span-3">
                                    <textarea :name="`type_services[${index}][description]`" x-model="ts.description" rows="2" placeholder="Description" class="w-full rounded bg-transparent border border-cyan-500/10 px-3 py-2 text-white mt-2"></textarea>
                                </div>
                            </div>
                            <div class="pl-3 mt-2">
                                <button type="button" @click="removeType(index)" class="text-red-400">Supprimer</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <div class="flex items-center space-x-2">
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2 rounded-lg border border-gray-700 text-gray-300">Annuler</a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-cyan-600 text-white">Créer</button>
            </div>
                @error('icone') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
            </div>


<script>
function serviceForm(){
    return {
        types: (function(){
            try{
                return JSON.parse(decodeURIComponent("{{ urlencode(json_encode(old('type_services', []))) }}")) || []
            }catch(e){ return [] }
        })().map(t=>({
            id: t.id || null,
            nom: t.nom || '',
            description: t.description || '',
            prix: t.prix || '',
            duree_minutes: t.duree_minutes || ''
        })),
        addType(){ this.types.push({id:null, nom:'', description:'', prix:'', duree_minutes:''}) },
        removeType(i){ this.types.splice(i,1) }
    }
}
</script>

@endsection
                <button type="submit" class="px-4 py-2 rounded-lg bg-cyan-600 text-white">Créer</button>
            </div>
        </form>
    </x-card>
</div>
@endsection
