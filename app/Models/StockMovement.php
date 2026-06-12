<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'fournisseur_id',
        'caisse_id',
        'quantite',
        'prix_achat',
        'date_peremption',
        'numero_lot',
        'note',
    ];

    protected $casts = [
        'date_peremption' => 'date',
        'quantite'        => 'decimal:2',
        'prix_achat'      => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
