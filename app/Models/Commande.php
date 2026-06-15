<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Commande extends Model
{
    protected $fillable = [
        'restaurant_id', 'numero', 'caisse_id', 'session_caisse_id', 'user_id',
        'table_numero', 'client_nom', 'statut', 'note', 'montant_total',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
    ];

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(CommandeProduit::class);
    }

    public function scopeForRestaurant($query, $restaurantId)
    {
        return $query->where('restaurant_id', $restaurantId);
    }

    public function mouvement(): HasOne
    {
        return $this->hasOne(MouvementCaisse::class);
    }

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function sessionCaisse(): BelongsTo
    {
        return $this->belongsTo(SessionCaisse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recalculerTotal(): void
    {
        $this->update(['montant_total' => $this->items()->sum('sous_total')]);
    }

    public function estModifiable(): bool
    {
        return in_array($this->statut, ['en_attente', 'en_preparation', 'servie']);
    }

    public static function genererNumero(): string
    {
        $restaurantId = auth()->user()?->restaurant_id;
        $date         = now()->format('Ymd');
        $derniere     = static::whereDate('created_at', today())
            ->when($restaurantId, fn($q) => $q->where('restaurant_id', $restaurantId))
            ->count() + 1;
        return sprintf('CMD-%s-%04d', $date, $derniere);
    }
}
