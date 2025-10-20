@extends('layouts.app')

@section('title', 'Prendre Rendez-vous')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card>
        <x-slot name="header">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold font-orbitron text-white">Prendre Rendez-vous</h2>
                    <p class="text-sm text-gray-400">Remplissez le formulaire pour réserver votre consultation</p>
                </div>
            </div>
        </x-slot>

        <form action="{{ route('patient.rendez-vous.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Service Selection -->
            <div>
                <label for="service_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Service <span class="text-red-400">*</span>
                </label>
                <select 
                    id="service_id" 
                    name="service_id" 
                    required
                    x-data="{ selectedService: '' }"
                    x-model="selectedService"
                    @change="
                        fetch(`/api/services/${selectedService}/type-services`)
                            .then(r => r.json())
                            .then(data => {
                                const select = document.getElementById('type_service_id');
                                select.innerHTML = '<option value=\"\">-- Sélectionnez un type de service --</option>';
                                data.forEach(ts => {
                                    select.innerHTML += `<option value=\"${ts.id}\">${ts.nom} - ${ts.prix.toLocaleString()} FCFA (${ts.duree_minutes} min)</option>`;
                                });
                            })
                    "
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="">-- Sélectionnez un service --</option>
                    @foreach($services as $service)
                    <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                        {{ $service->nom }}
                    </option>
                    @endforeach
                </select>
                @error('service_id')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Type Service Selection -->
            <div>
                <label for="type_service_id" class="block text-sm font-medium text-gray-300 mb-2">
                    Type de Service <span class="text-red-400">*</span>
                </label>
                <select 
                    id="type_service_id" 
                    name="type_service_id" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="">-- Sélectionnez d'abord un service --</option>
                </select>
                @error('type_service_id')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Date -->
            <div>
                <label for="date_rdv" class="block text-sm font-medium text-gray-300 mb-2">
                    Date du Rendez-vous <span class="text-red-400">*</span>
                </label>
                <input 
                    type="date" 
                    id="date_rdv" 
                    name="date_rdv" 
                    min="{{ date('Y-m-d') }}"
                    value="{{ old('date_rdv') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                @error('date_rdv')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Time -->
            <div>
                <label for="heure_rdv" class="block text-sm font-medium text-gray-300 mb-2">
                    Heure du Rendez-vous <span class="text-red-400">*</span>
                </label>
                <select 
                    id="heure_rdv" 
                    name="heure_rdv" 
                    required
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all"
                >
                    <option value="">-- Sélectionnez une heure --</option>
                    @for($h = 8; $h <= 17; $h++)
                        @foreach(['00', '30'] as $m)
                            @php
                            $time = sprintf('%02d:%s', $h, $m);
                            @endphp
                            @if($h < 17 || ($h == 17 && $m == '00'))
                            <option value="{{ $time }}" {{ old('heure_rdv') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                            @endif
                        @endforeach
                    @endfor
                </select>
                @error('heure_rdv')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-400 mt-1">Horaires d'ouverture: 08:00 - 17:00</p>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-300 mb-2">
                    Notes / Symptômes (optionnel)
                </label>
                <textarea 
                    id="notes" 
                    name="notes" 
                    rows="4"
                    placeholder="Décrivez vos symptômes ou toute information utile pour le médecin..."
                    class="w-full px-4 py-3 rounded-xl bg-gray-800/50 border border-cyan-500/30 text-white placeholder-gray-500 focus:border-cyan-500 focus:ring-2 focus:ring-cyan-500/50 transition-all resize-none"
                >{{ old('notes') }}</textarea>
                @error('notes')
                <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a 
                    href="{{ route('patient.dashboard') }}" 
                    class="px-6 py-3 rounded-xl bg-gray-700/50 border border-gray-600/50 text-gray-300 hover:bg-gray-700 transition-all"
                >
                    Annuler
                </a>
                <button 
                    type="submit" 
                    class="px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-semibold hover:shadow-[0_0_30px_rgba(6,182,212,0.5)] transition-all"
                >
                    Confirmer le Rendez-vous
                </button>
            </div>
        </form>
    </x-card>

    <!-- Information Card -->
    <x-card class="mt-6">
        <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-2">Informations importantes</h4>
                <ul class="text-sm text-gray-400 space-y-1">
                    <li>• Votre rendez-vous sera confirmé par le personnel médical dans les 24 heures</li>
                    <li>• Veuillez arriver 10 minutes avant l'heure prévue</li>
                    <li>• En cas d'empêchement, merci d'annuler au moins 24h à l'avance</li>
                    <li>• N'oubliez pas d'apporter votre carte d'identité et vos documents médicaux</li>
                </ul>
            </div>
        </div>
    </x-card>
</div>

@push('scripts')
<script>
    // Si un service était sélectionné (old input), recharger les types de services
    document.addEventListener('DOMContentLoaded', function() {
        const serviceId = "{{ old('service_id') }}";
        const typeServiceId = "{{ old('type_service_id') }}";
        
        if (serviceId) {
            fetch(`/api/services/${serviceId}/type-services`)
                .then(r => r.json())
                .then(data => {
                    const select = document.getElementById('type_service_id');
                    select.innerHTML = '<option value="">-- Sélectionnez un type de service --</option>';
                    data.forEach(ts => {
                        const selected = ts.id == typeServiceId ? 'selected' : '';
                        select.innerHTML += `<option value="${ts.id}" ${selected}>${ts.nom} - ${ts.prix.toLocaleString()} FCFA (${ts.duree_minutes} min)</option>`;
                    });
                });
        }
    });
</script>
@endpush
@endsection
