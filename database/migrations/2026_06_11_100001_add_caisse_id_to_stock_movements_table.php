<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->foreignId('caisse_id')->nullable()->constrained('caisses')->nullOnDelete()->after('fournisseur_id');
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Caisse::class);
            $table->dropColumn('caisse_id');
        });
    }
};
