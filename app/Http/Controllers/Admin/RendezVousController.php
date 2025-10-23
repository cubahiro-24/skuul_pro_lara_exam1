<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use App\Models\User;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rendezVous = RendezVous::with(['utilisateur', 'medecin', 'typeService.service'])
            ->orderBy('date_rdv', 'desc')
            ->orderBy('heure_rdv', 'desc')
            ->paginate(15);

        return view('admin.rendez-vous.index', compact('rendezVous'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $rendezVous)
    {
        $rendezVous->load(['utilisateur', 'medecin', 'typeService.service']);

        $medecins = User::whereHas('role', function ($q) {
            $q->where('nom', 'Medecin');
        })->get();

        return view('admin.rendez-vous.show', compact('rendezVous', 'medecins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RendezVous $rendezVous)
    {
        $data = $request->validate([
            'statut' => 'required|in:en_attente,confirme,termine,annule',
            'medecin_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!empty($data['medecin_id'])) {
            $med = User::find($data['medecin_id']);
            if (!$med || $med->role?->nom !== 'Medecin') {
                return back()->with('error', 'Médecin invalide sélectionné.');
            }
            $rendezVous->medecin_id = $data['medecin_id'];
        }

        $rendezVous->statut = $data['statut'];
        if (array_key_exists('notes', $data)) {
            $rendezVous->notes = $data['notes'];
        }
        $rendezVous->save();

        return redirect()->route('admin.rendez-vous.show', $rendezVous)->with('success', 'Rendez-vous mis à jour.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RendezVous $rendezVous)
    {
        $rendezVous->delete();
        return redirect()->route('admin.rendez-vous.index')->with('success', 'Rendez-vous supprimé.');
    }
}
