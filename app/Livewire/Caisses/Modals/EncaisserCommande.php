<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use App\Models\Commande;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EncaisserCommande extends ModalComponent
{
    public Commande $commande;

    public string $mode_paiement    = 'especes';
    public string $montant_recu     = '';
    public string $reference_mobile = '';
    public string $note             = '';
    public float  $monnaie_rendue   = 0;

    public function mount(): void
    {
        Gate::authorize('Encaisser Commande');
        $this->commande->loadMissing(['items.produit', 'caisse']);
    }

    public function updatedMontantRecu(): void
    {
        $recu = (float) $this->montant_recu;
        $this->monnaie_rendue = max(0, $recu - (float) $this->commande->montant_total);
    }

    public function encaisser(): void
    {
        Gate::authorize('Encaisser Commande');

        $rules = [
            'mode_paiement' => ['required', 'in:especes,mobile_money'],
        ];

        if ($this->mode_paiement === 'especes') {
            $rules['montant_recu'] = ['required', 'numeric', 'min:' . $this->commande->montant_total];
        } else {
            $rules['reference_mobile'] = ['nullable', 'string', 'max:100'];
        }

        $this->validate($rules, [
            'montant_recu.required' => 'Le montant reçu est obligatoire.',
            'montant_recu.min'      => 'Le montant reçu est insuffisant (minimum ' . number_format((float) $this->commande->montant_total, 0, ',', ' ') . ' FCFA).',
        ]);

        $caisse = Caisse::where('type', $this->mode_paiement)->where('statut', 'active')->first();

        if (! $caisse) {
            $this->addError('mode_paiement', 'Aucune caisse active trouvée.');
            return;
        }

        $paiement = [
            'mode_paiement'    => $this->mode_paiement,
            'montant_recu'     => $this->mode_paiement === 'especes' ? (float) $this->montant_recu : null,
            'monnaie_rendue'   => $this->mode_paiement === 'especes' ? $this->monnaie_rendue : null,
            'reference_mobile' => $this->mode_paiement === 'mobile_money' ? $this->reference_mobile : null,
            'note'             => $this->note ?: null,
        ];

        $caisse->encaisser($this->commande, $paiement);

        $this->dispatch('commande-encaissee');
        $this->closeModal();
    }
}
