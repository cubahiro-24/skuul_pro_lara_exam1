<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['rechargement', 'paiement', 'remboursement', 'retrait']); // Type de transaction
            $table->decimal('montant', 15, 2); // Montant en FBU
            $table->decimal('solde_avant', 15, 2); // Solde avant transaction
            $table->decimal('solde_apres', 15, 2); // Solde après transaction
            $table->string('reference')->unique(); // Référence unique de la transaction
            $table->string('description')->nullable(); // Description de la transaction
            $table->enum('statut', ['en_attente', 'reussi', 'echoue'])->default('en_attente');
            $table->foreignId('rendez_vous_id')->nullable()->constrained('rendez_vous')->onDelete('set null'); // Lien avec RDV si paiement
            $table->string('methode_rechargement')->nullable(); // mobile_money, carte_bancaire, especes
            $table->text('metadata')->nullable(); // JSON pour infos supplémentaires
            $table->timestamps();
            
            $table->index(['wallet_id', 'created_at']);
            $table->index('reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
