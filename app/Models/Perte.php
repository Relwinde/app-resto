<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perte extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero', 'motif', 'user_id', 'note', 'valeur_estimee',
    ];

    protected $casts = [
        'valeur_estimee' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Perte $perte) {
            $perte->lignes()->delete();
        });
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function recalculerValeurEstimee(): void
    {
        $valeur = $this->lignes()->get()->sum(
            fn (StockMovement $ligne) => round(abs((float) $ligne->quantite) * (float) ($ligne->product?->prix_vente ?? 0), 2)
        );

        $this->update(['valeur_estimee' => $valeur]);
    }

    public static function genererNumero(): string
    {
        $date     = now()->format('Ymd');
        $derniere = static::whereDate('created_at', today())->count() + 1;
        return sprintf('PERTE-%s-%04d', $date, $derniere);
    }
}
