<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\Approvisionnement;
use App\Models\Fournisseur;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditApprovisionnement extends ModalComponent
{
    public Approvisionnement $approvisionnement;

    public $fournisseur_id = '';
    public $note           = '';

    public array $lignes = []; // [ ['id' => 12, 'nom_produit' => ..., 'unite' => ..., 'quantite' => ..., 'prix_achat' => ..., 'date_peremption' => ..., 'numero_lot' => ...], ... ]

    public function mount(): void
    {
        Gate::authorize('Modifier Approvisionnement');

        $this->approvisionnement->loadMissing('lignes.product');

        $this->fournisseur_id = $this->approvisionnement->fournisseur_id;
        $this->note           = $this->approvisionnement->note;

        $this->lignes = $this->approvisionnement->lignes->map(fn (StockMovement $ligne) => [
            'id'              => $ligne->id,
            'nom_produit'     => $ligne->product?->name,
            'unite'           => $ligne->product?->unite,
            'quantite'        => (string) $ligne->quantite,
            'prix_achat'      => $ligne->prix_achat !== null ? (string) $ligne->prix_achat : '',
            'date_peremption' => $ligne->date_peremption?->format('Y-m-d'),
            'numero_lot'      => $ligne->numero_lot,
        ])->all();
    }

    public function render()
    {
        return view('livewire.approvisionnements.modals.edit-approvisionnement', [
            'fournisseurs' => Fournisseur::orderBy('name')->get(),
        ]);
    }

    public function save(): void
    {
        Gate::authorize('Modifier Approvisionnement');

        $this->validate(
            [
                'fournisseur_id'               => ['nullable', 'exists:fournisseurs,id'],
                'note'                          => ['nullable', 'string', 'max:500'],
                'lignes.*.quantite'             => ['required', 'numeric', 'min:0.01'],
                'lignes.*.prix_achat'           => ['required', 'numeric', 'min:0.01'],
                'lignes.*.date_peremption'      => ['nullable', 'date'],
                'lignes.*.numero_lot'           => ['nullable', 'string', 'max:100'],
            ],
            [
                'lignes.*.quantite.required'   => 'La quantité est obligatoire.',
                'lignes.*.quantite.min'        => 'La quantité doit être supérieure à 0.',
                'lignes.*.prix_achat.required' => 'Le prix total est obligatoire.',
                'lignes.*.prix_achat.min'      => 'Le prix total doit être supérieur à 0.',
            ]
        );

        foreach ($this->lignes as $ligne) {
            StockMovement::find($ligne['id'])?->update([
                'quantite'        => $ligne['quantite'],
                'prix_achat'      => $ligne['prix_achat'] ?: null,
                'date_peremption' => $ligne['date_peremption'] ?: null,
                'numero_lot'      => $ligne['numero_lot'] ?: null,
                'note'            => $this->note ?: null,
            ]);
        }

        $this->approvisionnement->update([
            'fournisseur_id' => $this->fournisseur_id ?: null,
            'note'           => $this->note ?: null,
        ]);

        // Volontaire : l'édition ne modifie jamais le solde de caisse, cf. décision produit —
        // seule la suppression déclenche un remboursement.
        $this->approvisionnement->recalculerTotal();

        $this->dispatch('approvisionnement-updated');
        $this->closeModal();
    }
}
