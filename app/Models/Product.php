<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Product extends Model
{
    protected $fillable = [
        'restaurant_id',
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

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeForRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function commandeItems(): HasMany
    {
        return $this->hasMany(CommandeProduit::class);
    }

    public function getStockActuelAttribute(): float
    {
        $entrees = (float) $this->stockMovements()->sum('quantite');
        $sorties = (float) $this->commandeItems()
            ->whereHas('commande', fn ($q) => $q->where('statut', 'payee'))
            ->sum('quantite');
        return $entrees - $sorties;
    }
}
