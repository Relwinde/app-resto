<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Caisse;
use App\Models\Commande;
use App\Models\CommandeProduit;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class PayerCommande extends ModalComponent
{
    public array  $panier       = [];
    public float  $total        = 0;
    public ?int   $caisse_id    = null;
    public ?int   $session_id   = null;
    public string $table_numero = '';
    public string $client_nom   = '';

    public string $mode_paiement   = 'especes';
    public string $montant_recu    = '';
    public string $reference_mobile = '';
    public string $note            = '';

    public float $monnaie_rendue = 0;

    public function updatedMontantRecu(): void
    {
        $recu = (float) $this->montant_recu;
        $this->monnaie_rendue = max(0, $recu - $this->total);
    }

    public function render()
    {
        return view('livewire.caisses.modals.payer-commande');
    }

    public function payer(): void
    {
        Gate::authorize('Encaisser Commande');

        if (! Caisse::sessionOuverte()) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $rules = [
            'mode_paiement' => ['required', 'in:especes,mobile_money'],
        ];

        if ($this->mode_paiement === 'especes') {
            $rules['montant_recu'] = ['required', 'numeric', 'min:' . $this->total];
        } else {
            $rules['reference_mobile'] = ['nullable', 'string', 'max:100'];
        }

        $this->validate($rules, [
            'montant_recu.required' => 'Le montant reçu est obligatoire.',
            'montant_recu.min'      => 'Le montant reçu est insuffisant (minimum ' . number_format($this->total, 0, ',', ' ') . ' FCFA).',
        ]);

        $caisse = $this->caisse_id ? Caisse::find($this->caisse_id) : Caisse::where('statut', 'active')->first();

        if (! $caisse) {
            $this->addError('mode_paiement', 'Aucune caisse active trouvée.');
            return;
        }

        $commande = Commande::create([
            'numero'            => Commande::genererNumero(),
            'caisse_id'         => $caisse->id,
            'session_caisse_id' => $this->session_id,
            'user_id'           => auth()->id(),
            'table_numero'      => $this->table_numero ?: null,
            'client_nom'        => $this->client_nom ?: null,
            'statut'            => 'en_attente',
            'montant_total'     => $this->total,
        ]);

        foreach ($this->panier as $item) {
            CommandeProduit::create([
                'commande_id'   => $commande->id,
                'product_id'    => $item['product_id'],
                'quantite'      => $item['quantite'],
                'prix_unitaire' => $item['prix_unitaire'],
                'sous_total'    => $item['sous_total'],
            ]);
        }

        $paiement = [
            'mode_paiement'    => $this->mode_paiement,
            'montant_recu'     => $this->mode_paiement === 'especes' ? (float) $this->montant_recu : null,
            'monnaie_rendue'   => $this->mode_paiement === 'especes' ? $this->monnaie_rendue : null,
            'reference_mobile' => $this->mode_paiement === 'mobile_money' ? $this->reference_mobile : null,
            'note'             => $this->note ?: null,
        ];

        $caisse->encaisser($commande, $paiement);

        $this->dispatch('commande-payee');
        $this->closeModal();
    }
}
