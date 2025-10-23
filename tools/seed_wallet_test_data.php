#!/usr/bin/env php
<?php

/**
 * Script pour créer des données de test pour le système de wallet
 * Usage: php tools/seed_wallet_test_data.php
 */

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Facades\Artisan;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\RendezVous;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;

echo "🚀 Création des données de test pour le système de wallet...\n\n";

try {
    DB::beginTransaction();

    // Récupérer tous les patients
    $patients = User::whereHas('role', function($q) {
        $q->where('nom', 'Patient');
    })->get();

    if ($patients->isEmpty()) {
        echo "❌ Aucun patient trouvé dans la base de données.\n";
        echo "💡 Exécutez d'abord: php tools/seed_test_data.php\n";
        exit(1);
    }

    echo "👥 {$patients->count()} patient(s) trouvé(s)\n\n";

    foreach ($patients as $patient) {
        echo "📝 Patient: {$patient->name} (#{$patient->id})\n";

        // Créer ou récupérer le wallet
        $wallet = $patient->getOrCreateWallet();
        echo "   💳 Wallet créé/récupéré (ID: {$wallet->id})\n";

        // Générer des recharges aléatoires
        $nombreRecharges = rand(2, 5);
        $soldeTotal = 0;

        for ($i = 0; $i < $nombreRecharges; $i++) {
            $montant = rand(10, 200) * 1000; // Entre 10,000 et 200,000 FBU
            $methodes = ['mobile_money', 'carte_bancaire', 'especes'];
            $methode = $methodes[array_rand($methodes)];

            $metadata = [
                'methode_rechargement' => $methode,
                'description' => 'Rechargement de test',
            ];

            if ($methode === 'mobile_money') {
                $metadata['telephone'] = '+25779' . rand(100000, 999999);
                $metadata['operateur'] = rand(0, 1) ? 'Ecocash' : 'Lumicash';
            } elseif ($methode === 'carte_bancaire') {
                $metadata['numero_carte'] = '**** **** **** ' . rand(1000, 9999);
            }

            $transaction = $wallet->credit($montant, 'rechargement', $metadata);
            $soldeTotal += $montant;

            $numero = $i + 1;
            echo "      ✅ Rechargement #{$numero}: " . number_format($montant, 0, ',', ' ') . " FBU via {$methode}\n";
        }

        echo "   💰 Solde total après recharges: " . number_format($wallet->fresh()->solde, 0, ',', ' ') . " FBU\n";

        // Créer quelques paiements de rendez-vous
        $rendezVous = RendezVous::where('utilisateur_id', $patient->id)
            ->whereDoesntHave('paiements')
            ->limit(rand(1, 3))
            ->get();

        if ($rendezVous->isNotEmpty()) {
            foreach ($rendezVous as $rdv) {
                $montant = $rdv->typeService->prix;
                
                // Vérifier si le wallet a assez de solde
                if ($wallet->fresh()->solde >= $montant) {
                    $transaction = $wallet->debit($montant, 'paiement', [
                        'rendez_vous_id' => $rdv->id,
                        'type_service' => $rdv->typeService->nom,
                        'description' => 'Paiement pour ' . $rdv->typeService->nom,
                    ]);

                    // Créer le paiement
                    $paiement = Paiement::create([
                        'rendez_vous_id' => $rdv->id,
                        'montant' => $montant,
                        'mode' => 'wallet',
                        'statut' => 'reussi',
                        'date_paiement' => now(),
                        'reference' => $transaction->reference,
                    ]);

                    echo "      💳 Paiement RDV #{$rdv->id}: " . number_format($montant, 0, ',', ' ') . " FBU\n";
                }
            }
        }

        $walletFinal = $wallet->fresh();
        echo "   📊 Solde final: " . number_format($walletFinal->solde, 0, ',', ' ') . " FBU\n";
        echo "   📈 Total transactions: {$walletFinal->transactions()->count()}\n";
        echo "\n";
    }

    DB::commit();

    echo "✅ Données de test créées avec succès !\n\n";
    echo "📊 Résumé:\n";
    echo "   - Wallets: " . Wallet::count() . "\n";
    echo "   - Transactions: " . Transaction::count() . "\n";
    echo "   - Paiements via wallet: " . Paiement::where('mode', 'wallet')->count() . "\n";
    echo "\n";
    echo "🎉 Vous pouvez maintenant tester le système de wallet !\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Erreur: " . $e->getMessage() . "\n";
    echo "📍 Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}
