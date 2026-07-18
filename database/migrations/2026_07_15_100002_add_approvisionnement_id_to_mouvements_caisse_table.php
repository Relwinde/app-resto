<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mouvements_caisse', function (Blueprint $table) {
            $table->foreignId('approvisionnement_id')->nullable()
                ->after('depense_id')
                ->constrained('approvisionnements')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mouvements_caisse', function (Blueprint $table) {
            $table->dropConstrainedForeignId('approvisionnement_id');
        });
    }
};
