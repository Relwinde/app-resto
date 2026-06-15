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

    public static function sessionOuverte(): bool
    {
        return static::where('statut', 'active')
            ->whereHas('sessions', fn($q) => $q->where('statut', 'ouverte'))
            ->exists();
    }

    public function retirer(
        float $montant,
        ?string $note = null,
        ?int $stockMovementId = null,
        ?int $depenseId = null,
        string $type = 'retrait'
    ): MouvementCaisse {
        $soldeAvant = (float) $this->solde_actuel;

        if ($montant > $soldeAvant) {
            throw new \RuntimeException(
                "Solde insuffisant dans la caisse « {$this->nom} » (solde : {$soldeAvant} FCFA, demandé : {$montant} FCFA)."
            );
        }

        $soldeApres = $soldeAvant - $montant;

        $mouvement = $this->mouvements()->create([
            'session_caisse_id' => $this->sessionActive()?->id,
            'stock_movement_id' => $stockMovementId,
            'depense_id'        => $depenseId,
            'user_id'           => auth()->id(),
            'type'              => $type,
            'montant'           => $montant,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeApres,
            'mode_paiement'     => $this->type,
            'note'              => $note,
        ]);

        $this->update(['solde_actuel' => $soldeApres]);

        return $mouvement;
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

    public function deposer(float $montant, ?string $note = null): MouvementCaisse
    {
        $soldeAvant = (float) $this->solde_actuel;
        $soldeApres = $soldeAvant + $montant;

        $mouvement = $this->mouvements()->create([
            'session_caisse_id' => $this->sessionActive()?->id,
            'user_id'           => auth()->id(),
            'type'              => 'depot',
            'montant'           => $montant,
            'solde_avant'       => $soldeAvant,
            'solde_apres'       => $soldeApres,
            'mode_paiement'     => $this->type,
            'note'              => $note,
        ]);

        $this->update(['solde_actuel' => $soldeApres]);

        return $mouvement;
    }
}
