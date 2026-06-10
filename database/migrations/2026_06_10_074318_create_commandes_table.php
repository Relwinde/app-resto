<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero')->unique();
            $table->foreignId('caisse_id')->constrained('caisses')->restrictOnDelete();
            $table->foreignId('session_caisse_id')->nullable()->constrained('sessions_caisse')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('table_numero')->nullable();
            $table->string('client_nom')->nullable();
            $table->enum('statut', ['en_attente', 'en_preparation', 'servie', 'payee', 'annulee'])->default('en_attente');
            $table->string('note')->nullable();
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
