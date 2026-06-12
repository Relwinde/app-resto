<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mouvements_caisse', function (Blueprint $table) {
            $table->foreignId('depense_id')->nullable()->constrained('depenses')->nullOnDelete()->after('stock_movement_id');
        });
    }

    public function down(): void
    {
        Schema::table('mouvements_caisse', function (Blueprint $table) {
            $table->dropForeign(['depense_id']);
            $table->dropColumn('depense_id');
        });
    }
};
