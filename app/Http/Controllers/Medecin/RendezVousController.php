<?php

namespace App\Http\Controllers\Medecin;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = auth()->user()->rendezVousMedecin()
            ->with(['utilisateur', 'typeService.service', 'paiements']);

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrer par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_rdv', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_rdv', '<=', $request->date_fin);
        }

        // Filtrer par date spécifique
        if ($request->filled('date')) {
            $query->whereDate('date_rdv', $request->date);
        }

        $rendezVous = $query->orderBy('date_rdv')
            ->orderBy('heure_rdv')
            ->paginate(15);

        // Statistiques pour les filtres
        $stats = [
            'total' => auth()->user()->rendezVousMedecin()->count(),
            'en_attente' => auth()->user()->rendezVousMedecin()->where('statut', 'en_attente')->count(),
            'confirme' => auth()->user()->rendezVousMedecin()->where('statut', 'confirme')->count(),
            'termine' => auth()->user()->rendezVousMedecin()->where('statut', 'termine')->count(),
            'annule' => auth()->user()->rendezVousMedecin()->where('statut', 'annule')->count(),
        ];

        return view('medecin.rendez-vous.index', compact('rendezVous', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient bien au médecin connecté
        if ($rendezVous->medecin_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à voir ce rendez-vous.');
        }

        $rendezVous->load([
            'utilisateur',
            'typeService.service',
            'paiements.facture'
        ]);

        return view('medecin.rendez-vous.show', compact('rendezVous'));
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, RendezVous $rendezVous)
    {
        // Vérifier que le RDV appartient bien au médecin connecté
        if ($rendezVous->medecin_id !== auth()->id()) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier ce rendez-vous.');
        }

        $request->validate([
            'statut' => 'required|in:en_attente,confirme,termine,annule'
        ]);

        $nouveauStatut = $request->statut;
        $ancienStatut = $rendezVous->statut;

        // Valider les transitions de statut autorisées
        $transitionsAutorisees = [
            'en_attente' => ['confirme', 'annule'],
            'confirme' => ['termine', 'annule'],
            'termine' => [], // Un RDV terminé ne peut pas changer de statut
            'annule' => [], // Un RDV annulé ne peut pas changer de statut
        ];

        if (!in_array($nouveauStatut, $transitionsAutorisees[$ancienStatut] ?? [])) {
            return back()->with('error', 'Transition de statut non autorisée.');
        }

        try {
            DB::beginTransaction();

            $rendezVous->update(['statut' => $nouveauStatut]);

            // Messages selon le statut
            $messages = [
                'confirme' => 'Rendez-vous confirmé avec succès.',
                'termine' => 'Rendez-vous marqué comme terminé.',
                'annule' => 'Rendez-vous annulé.',
            ];

            DB::commit();

            return back()->with('success', $messages[$nouveauStatut] ?? 'Statut mis à jour.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la mise à jour du statut : ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Les médecins ne peuvent pas supprimer des RDV, seulement les annuler
        abort(403, 'Action non autorisée.');
    }
}
