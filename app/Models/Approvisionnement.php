<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Approvisionnement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero', 'fournisseur_id', 'caisse_id', 'session_caisse_id', 'user_id',
        'montant_total', 'note',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Approvisionnement $approvisionnement) {
            $approvisionnement->lignes()->delete();
        });
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
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

    public function mouvement(): HasOne
    {
        return $this->hasOne(MouvementCaisse::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function recalculerTotal(): void
    {
        $this->update(['montant_total' => $this->lignes()->sum('prix_achat')]);
    }

    public static function genererNumero(): string
    {
        $date     = now()->format('Ymd');
        $derniere = static::whereDate('created_at', today())->count() + 1;
        return sprintf('APP-%s-%04d', $date, $derniere);
    }
}
