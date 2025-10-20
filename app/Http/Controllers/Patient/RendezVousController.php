<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\Service;
use App\Models\TypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rendezVous = Auth::user()->rendezVousPatient()
            ->with(['typeService.service', 'medecin'])
            ->orderBy('date_rdv', 'desc')
            ->orderBy('heure_rdv', 'desc')
            ->paginate(10);

        return view('patient.rendez-vous.index', compact('rendezVous'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $services = Service::with('typeServices')->get();
        return view('patient.rendez-vous.create', compact('services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'type_service_id' => 'required|exists:type_services,id',
            'date_rdv' => 'required|date|after_or_equal:today',
            'heure_rdv' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Vérifier que le type de service appartient bien au service sélectionné
        $typeService = TypeService::where('id', $validated['type_service_id'])
            ->where('service_id', $validated['service_id'])
            ->firstOrFail();

        // Assigner un médecin aléatoire pour le moment
        $medecin = \App\Models\User::whereHas('role', function($q) {
            $q->where('nom', 'Medecin');
        })->inRandomOrder()->first();

        if (!$medecin) {
            return back()->with('error', 'Aucun médecin disponible pour le moment.');
        }

        $rendezVous = RendezVous::create([
            'utilisateur_id' => Auth::id(),
            'medecin_id' => $medecin->id,
            'type_service_id' => $validated['type_service_id'],
            'date_rdv' => $validated['date_rdv'],
            'heure_rdv' => $validated['heure_rdv'],
            'statut' => 'en_attente',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('patient.rendez-vous.index')
            ->with('success', 'Votre rendez-vous a été créé avec succès. Un médecin le confirmera bientôt.');
    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $rendezVous)
    {
        // Vérifier que le rendez-vous appartient bien au patient connecté
        if ($rendezVous->utilisateur_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $rendezVous->load(['typeService.service', 'medecin']);
        return view('patient.rendez-vous.show', compact('rendezVous'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RendezVous $rendezVous)
    {
        // Vérifier que le rendez-vous appartient bien au patient connecté
        if ($rendezVous->utilisateur_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Ne peut annuler que les rendez-vous en attente ou confirmés
        if (!in_array($rendezVous->statut, ['en_attente', 'confirme'])) {
            return back()->with('error', 'Ce rendez-vous ne peut plus être annulé.');
        }

        $rendezVous->update(['statut' => 'annule']);

        return back()->with('success', 'Rendez-vous annulé avec succès.');
    }
}
