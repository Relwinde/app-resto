<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caisse extends Model
{
    protected $fillable = ['nom', 'type', 'solde_actuel', 'statut'];

    protected $casts = [
        'solde_actuel' => 'decimal:2',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(SessionCaisse::class);
    }

    public function commandes(): HasMany
    {
        return $this->hasMany(Commande::class);
    }

    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementCaisse::class);
    }

    public function sessionActive(): ?SessionCaisse
    {
        return $this->sessions()->where('statut', 'ouverte')->latest()->first();
    }

    public function encaisser(Commande $commande, array $paiement): MouvementCaisse
    {
        $soldeAvant = (float) $this->solde_actuel;
        $montant    = (float) $commande->montant_total;
        $soldeApres = $soldeAvant + $montant;

        $mouvement = $this->mouvements()->create([
            'session_caisse_id' => $this->sessionActive()?->id,
            'commande_id'       => $commande->id,
            'user_id'           => auth()->id(),
            'type'              => 'encaissement',
            'montant'           => $montant,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeApres,
            'mode_paiement'     => $paiement['mode_paiement'],
            'montant_recu'      => $paiement['montant_recu'] ?? null,
            'monnaie_rendue'    => $paiement['monnaie_rendue'] ?? null,
            'reference_mobile'  => $paiement['reference_mobile'] ?? null,
            'note'              => $paiement['note'] ?? null,
        ]);

        $this->update(['solde_actuel' => $soldeApres]);
        $commande->update(['statut' => 'payee']);

        return $mouvement;
    }
}
