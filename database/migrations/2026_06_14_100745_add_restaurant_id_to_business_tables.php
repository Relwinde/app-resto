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
        $tables = [
            'caisses',
            'sessions_caisse',
            'commandes',
            'commande_produits',
            'mouvements_caisse',
            'categories',
            'products',
            'fournisseurs',
            'stock_movements',
            'depenses',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    if (!Schema::hasColumn($table, 'restaurant_id')) {
                        $t->foreignId('restaurant_id')->nullable()->constrained('restaurants')->nullOnDelete();
                        $t->index('restaurant_id');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'caisses',
            'sessions_caisse',
            'commandes',
            'commande_produits',
            'mouvements_caisse',
            'categories',
            'products',
            'fournisseurs',
            'stock_movements',
            'depenses',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'restaurant_id')) {
                Schema::table($table, function (Blueprint $t) use ($table) {
                    $t->dropForeign([$table . '_restaurant_id_foreign']);
                    $t->dropColumn('restaurant_id');
                });
            }
        }
    }
};
