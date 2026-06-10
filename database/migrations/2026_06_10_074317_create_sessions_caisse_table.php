<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions_caisse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caisse_id')->constrained('caisses')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('fond_ouverture', 10, 2)->default(0);
            $table->decimal('fond_fermeture', 10, 2)->nullable();
            $table->enum('statut', ['ouverte', 'fermee'])->default('ouverte');
            $table->timestamp('ferme_le')->nullable();
            $table->string('note_ouverture')->nullable();
            $table->string('note_fermeture')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions_caisse');
    }
};
