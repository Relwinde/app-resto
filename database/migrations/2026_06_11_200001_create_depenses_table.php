<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->nullable()->constrained('caisses')->nullOnDelete();
            $table->foreignId('session_caisse_id')->nullable()->constrained('sessions_caisse')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('paye_par')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('montant', 10, 2);
            $table->string('motif');
            $table->string('beneficiaire')->nullable();
            $table->text('note')->nullable();
            $table->enum('statut', ['edite', 'en_attente', 'valide', 'paye'])->default('edite');
            $table->timestamp('valide_le')->nullable();
            $table->timestamp('paye_le')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
