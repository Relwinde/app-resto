<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mouvements_caisse', function (Blueprint $table) {
            $table->foreignId('stock_movement_id')->nullable()->constrained('stock_movements')->nullOnDelete()->after('commande_id');
        });
    }

    public function down(): void
    {
        Schema::table('mouvements_caisse', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\StockMovement::class);
            $table->dropColumn('stock_movement_id');
        });
    }
};
