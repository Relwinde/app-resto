<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $lignes = DB::table('stock_movements')->whereNull('approvisionnement_id')->orderBy('id')->get();

        foreach ($lignes as $ligne) {
            $montant = $ligne->prix_achat !== null
                ? round(((float) $ligne->quantite) * ((float) $ligne->prix_achat), 2)
                : 0.0;

            $approId = DB::table('approvisionnements')->insertGetId([
                'numero'            => 'APP-LEGACY-' . str_pad((string) $ligne->id, 6, '0', STR_PAD_LEFT),
                'fournisseur_id'    => $ligne->fournisseur_id,
                'caisse_id'         => $ligne->caisse_id,
                'session_caisse_id' => null,
                'user_id'           => null,
                'montant_total'     => $montant,
                'note'              => $ligne->note,
                'created_at'        => $ligne->created_at,
                'updated_at'        => $ligne->updated_at,
            ]);

            DB::table('stock_movements')->where('id', $ligne->id)->update([
                'approvisionnement_id' => $approId,
            ]);

            DB::table('mouvements_caisse')
                ->where('stock_movement_id', $ligne->id)
                ->update(['approvisionnement_id' => $approId]);
        }
    }

    public function down(): void
    {
        $ids = DB::table('approvisionnements')->where('numero', 'like', 'APP-LEGACY-%')->pluck('id');

        DB::table('mouvements_caisse')->whereIn('approvisionnement_id', $ids)->update(['approvisionnement_id' => null]);
        DB::table('stock_movements')->whereIn('approvisionnement_id', $ids)->update(['approvisionnement_id' => null]);
        DB::table('approvisionnements')->whereIn('id', $ids)->delete();
    }
};
