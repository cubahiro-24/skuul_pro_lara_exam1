<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    /**
     * Display a listing of patient payments.
     */
    public function index()
    {
        $paiements = Paiement::whereHas('rendezVous', function($q) {
            $q->where('utilisateur_id', Auth::id());
        })
        ->with(['rendezVous.typeService.service', 'rendezVous.medecin'])
        ->orderBy('date_paiement', 'desc')
        ->paginate(10);

        return view('patient.paiements.index', compact('paiements'));
    }

    /**
     * Display a listing of patient invoices.
     */
    public function factures()
    {
        $factures = Paiement::whereHas('rendezVous', function($q) {
            $q->where('utilisateur_id', Auth::id());
        })
        ->whereNotNull('reference')
        ->with(['rendezVous.typeService.service', 'rendezVous.medecin'])
        ->orderBy('date_paiement', 'desc')
        ->paginate(10);

        return view('patient.factures.index', compact('factures'));
    }
}
