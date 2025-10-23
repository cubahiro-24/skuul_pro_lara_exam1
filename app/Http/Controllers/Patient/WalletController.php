<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    /**
     * Afficher le portefeuille
     */
    public function index()
    {
        $wallet = Auth::user()->getOrCreateWallet();
        $transactions = $wallet->transactions()
            ->with('rendezVous.typeService.service')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $stats = [
            'total_recharge' => $wallet->transactions()
                ->where('type', 'rechargement')
                ->where('statut', 'reussi')
                ->sum('montant'),
            'total_depense' => $wallet->transactions()
                ->where('type', 'paiement')
                ->where('statut', 'reussi')
                ->sum('montant'),
            'total_transactions' => $wallet->transactions()->count(),
        ];

        return view('patient.wallet.index', compact('wallet', 'transactions', 'stats'));
    }

    /**
     * Formulaire de rechargement
     */
    public function recharger()
    {
        $wallet = Auth::user()->getOrCreateWallet();
        return view('patient.wallet.recharger', compact('wallet'));
    }

    /**
     * Traiter le rechargement
     */
    public function storeRechargement(Request $request)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric|min:1000|max:5000000', // Min 1000 FBU, Max 5M FBU
            'methode' => 'required|in:mobile_money,carte_bancaire,especes',
            'telephone' => 'required_if:methode,mobile_money|nullable|string',
            'numero_carte' => 'required_if:methode,carte_bancaire|nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $wallet = Auth::user()->getOrCreateWallet();

            // Simuler la validation du paiement (à remplacer par vraie API de paiement)
            $transaction = $wallet->credit(
                $validated['montant'],
                'rechargement',
                [
                    'description' => 'Rechargement du portefeuille',
                    'methode_rechargement' => $validated['methode'],
                    'telephone' => $validated['telephone'] ?? null,
                    'numero_carte' => $validated['numero_carte'] ?? null,
                ]
            );

            DB::commit();

            return redirect()->route('patient.wallet.index')
                ->with('success', 'Votre portefeuille a été rechargé de ' . number_format($validated['montant'], 0, ',', ' ') . ' FBU avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du rechargement: ' . $e->getMessage());
        }
    }

    /**
     * Historique des transactions
     */
    public function transactions()
    {
        $wallet = Auth::user()->getOrCreateWallet();
        $transactions = $wallet->transactions()
            ->with('rendezVous.typeService.service')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('patient.wallet.transactions', compact('wallet', 'transactions'));
    }
}
