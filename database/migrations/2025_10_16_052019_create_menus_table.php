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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('lien');
            $table->string('icone')->nullable();
            $table->integer('ordre')->default(0);
            $table->json('visible_pour')->nullable(); // Stocke les IDs de rôles autorisés
            $table->foreignId('parent_id')->nullable()->constrained('menus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
