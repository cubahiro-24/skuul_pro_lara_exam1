<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;

class PaiementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Paiement::with(['rendezVous.utilisateur', 'rendezVous.typeService', 'facture']);

        // Filtrer par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtrer par mode de paiement
        if ($request->filled('mode')) {
            $query->where('mode', $request->mode);
        }

        // Filtrer par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        // Recherche par référence ou nom patient
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('rendezVous.utilisateur', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('prenom', 'like', "%{$search}%");
                  });
            });
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate(20);

        // Statistiques
        $stats = [
            'total' => Paiement::count(),
            'reussi' => Paiement::where('statut', 'reussi')->count(),
            'en_attente' => Paiement::where('statut', 'en_attente')->count(),
            'echoue' => Paiement::where('statut', 'echoue')->count(),
            'montant_total' => Paiement::where('statut', 'reussi')->sum('montant'),
            'montant_mois' => Paiement::where('statut', 'reussi')
                ->whereMonth('date_paiement', now()->month)
                ->whereYear('date_paiement', now()->year)
                ->sum('montant'),
        ];

        return view('admin.paiements.index', compact('paiements', 'stats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Paiement $paiement)
    {
        $paiement->load([
            'rendezVous.utilisateur',
            'rendezVous.medecin',
            'rendezVous.typeService.service',
            'facture'
        ]);

        return view('admin.paiements.show', compact('paiement'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Les paiements ne peuvent pas être supprimés, seulement consultés
        abort(403, 'Les paiements ne peuvent pas être supprimés.');
    }
}
