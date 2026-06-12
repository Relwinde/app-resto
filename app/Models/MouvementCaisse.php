<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementCaisse extends Model
{
    protected $table = 'mouvements_caisse';

    protected $fillable = [
        'caisse_id', 'session_caisse_id', 'commande_id', 'stock_movement_id', 'depense_id', 'user_id',
        'type', 'montant', 'solde_avant', 'solde_apres',
        'mode_paiement', 'montant_recu', 'monnaie_rendue', 'reference_mobile', 'note',
    ];

    protected $casts = [
        'montant'        => 'decimal:2',
        'solde_avant'    => 'decimal:2',
        'solde_apres'    => 'decimal:2',
        'montant_recu'   => 'decimal:2',
        'monnaie_rendue' => 'decimal:2',
    ];

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function sessionCaisse(): BelongsTo
    {
        return $this->belongsTo(SessionCaisse::class);
    }

    public function commande(): BelongsTo
    {
        return $this->belongsTo(Commande::class);
    }

    public function stockMovement(): BelongsTo
    {
        return $this->belongsTo(StockMovement::class);
    }

    public function depense(): BelongsTo
    {
        return $this->belongsTo(Depense::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
