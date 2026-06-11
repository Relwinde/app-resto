<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditApprovisionnement extends ModalComponent
{
    public StockMovement $approvisionnement;

    public $product_id      = '';
    public $fournisseur_id  = '';
    public $quantite        = '';
    public $prix_achat      = '';
    public $date_peremption = '';
    public $numero_lot      = '';
    public $note            = '';

    public function mount(): void
    {
        Gate::authorize('Modifier Approvisionnement');
        $this->product_id      = $this->approvisionnement->product_id;
        $this->fournisseur_id  = $this->approvisionnement->fournisseur_id;
        $this->quantite        = $this->approvisionnement->quantite;
        $this->prix_achat      = $this->approvisionnement->prix_achat;
        $this->date_peremption = $this->approvisionnement->date_peremption?->format('Y-m-d');
        $this->numero_lot      = $this->approvisionnement->numero_lot;
        $this->note            = $this->approvisionnement->note;
    }

    public function render()
    {
        return view('livewire.approvisionnements.modals.edit-approvisionnement', [
            'produits'     => Product::where('is_suppliable', true)->orderBy('name')->get(),
            'fournisseurs' => Fournisseur::orderBy('name')->get(),
        ]);
    }

    public function save(): void
    {
        Gate::authorize('Modifier Approvisionnement');

        $this->validate(
            [
                'product_id'      => ['required', 'exists:products,id'],
                'fournisseur_id'  => ['nullable', 'exists:fournisseurs,id'],
                'quantite'        => ['required', 'numeric', 'min:0.01'],
                'prix_achat'      => ['required', 'numeric', 'min:0'],
                'date_peremption' => ['nullable', 'date'],
                'numero_lot'      => ['nullable', 'string', 'max:100'],
                'note'            => ['nullable', 'string', 'max:500'],
            ],
            [
                'product_id.required' => 'Le produit est obligatoire.',
                'quantite.required'   => 'La quantité est obligatoire.',
                'quantite.min'        => 'La quantité doit être supérieure à 0.',
            ]
        );

        $this->approvisionnement->update([
            'product_id'      => $this->product_id,
            'fournisseur_id'  => $this->fournisseur_id ?: null,
            'quantite'        => $this->quantite,
            'prix_achat'      => $this->prix_achat ?: null,
            'date_peremption' => $this->date_peremption ?: null,
            'numero_lot'      => $this->numero_lot ?: null,
            'note'            => $this->note ?: null,
        ]);

        $this->dispatch('approvisionnement-updated');
        $this->closeModal();
    }
}
