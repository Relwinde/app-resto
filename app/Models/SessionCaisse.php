<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SessionCaisse extends Model
{
    protected $table = 'sessions_caisse';

    protected $fillable = [
        'caisse_id', 'user_id', 'fond_ouverture', 'fond_fermeture',
        'statut', 'ferme_le', 'note_ouverture', 'note_fermeture',
    ];

    protected $casts = [
        'fond_ouverture' => 'decimal:2',
        'fond_fermeture' => 'decimal:2',
        'ferme_le'       => 'datetime',
    ];

    public function caisse(): BelongsTo
    {
        return $this->belongsTo(Caisse::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementCaisse::class);
    }

    public function estOuverte(): bool
    {
        return $this->statut === 'ouverte';
    }

    public function totalEncaisse(): float
    {
        return (float) $this->mouvements()->where('type', 'encaissement')->sum('montant');
    }

    public function fermer(float $fond_fermeture, ?string $note = null): void
    {
        $soldeAvant = (float) $this->caisse->solde_actuel;

        $this->update([
            'fond_fermeture'  => $fond_fermeture,
            'statut'          => 'fermee',
            'ferme_le'        => now(),
            'note_fermeture'  => $note,
        ]);

        $this->mouvements()->create([
            'caisse_id'    => $this->caisse_id,
            'user_id'      => auth()->id(),
            'type'         => 'fermeture',
            'montant'      => $fond_fermeture,
            'solde_avant'  => $soldeAvant,
            'solde_apres'  => $soldeAvant,
            'note'         => $note,
        ]);
    }
}
