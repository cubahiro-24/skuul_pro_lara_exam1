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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('solde', 15, 2)->default(0); // Solde en FBU
            $table->boolean('is_active')->default(true);
            $table->string('devise', 3)->default('FBU'); // Devise (Franc Burundais)
            $table->timestamps();
            
            $table->unique('user_id'); // Un seul wallet par utilisateur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
