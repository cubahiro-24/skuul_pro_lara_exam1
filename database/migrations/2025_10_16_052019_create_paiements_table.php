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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rendez_vous_id')->constrained('rendez_vous')->onDelete('cascade');
            $table->decimal('montant', 10, 2);
            $table->enum('mode', ['carte', 'mobile_money', 'especes'])->default('especes');
            $table->enum('statut', ['reussi', 'echoue', 'en_attente'])->default('en_attente');
            $table->dateTime('date_paiement')->nullable();
            $table->string('reference')->unique()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
