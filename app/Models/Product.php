<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category_id',
        'prix_vente',
        'prix_achat',
        'unite',
        'is_suppliable',
    ];

    protected $casts = [
        'is_suppliable' => 'boolean',
        'prix_vente'    => 'decimal:2',
        'prix_achat'    => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    // Stock actuel = somme des approvisionnements
    // À compléter quand la table ventes sera créée :
    // return $entrees - $this->ventes()->sum('quantite')
    public function getStockActuelAttribute(): float
    {
        return (float) $this->stockMovements()->sum('quantite');
    }
}
