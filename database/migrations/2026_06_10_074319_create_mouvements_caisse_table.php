<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements_caisse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('caisses')->restrictOnDelete();
            $table->foreignId('session_caisse_id')->nullable()->constrained('sessions_caisse')->nullOnDelete();
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->enum('type', ['encaissement', 'ouverture', 'fermeture', 'retrait', 'depot']);
            $table->decimal('montant', 10, 2);
            $table->decimal('solde_avant', 10, 2);
            $table->decimal('solde_apres', 10, 2);
            $table->enum('mode_paiement', ['especes', 'mobile_money'])->nullable();
            $table->decimal('montant_recu', 10, 2)->nullable();
            $table->decimal('monnaie_rendue', 10, 2)->nullable();
            $table->string('reference_mobile')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements_caisse');
    }
};
