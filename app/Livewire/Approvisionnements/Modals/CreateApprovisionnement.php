<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\StockMovement;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class CreateApprovisionnement extends ModalComponent
{
    use WithFileUploads;

    public $product_id      = '';
    public $fournisseur_id  = '';
    public $quantite        = '';
    public $prix_achat      = '';
    public $date_peremption = '';
    public $numero_lot      = '';
    public $note            = '';
    public $fichier         = null;

    public function render()
    {
        return view('livewire.approvisionnements.modals.create-approvisionnement', [
            'produits'     => Product::where('is_suppliable', true)->orderBy('name')->get(),
            'fournisseurs' => Fournisseur::orderBy('name')->get(),
        ]);
    }

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function create(): void
    {
        $this->validate(
            [
                'product_id'      => ['required', 'exists:products,id'],
                'fournisseur_id'  => ['nullable', 'exists:fournisseurs,id'],
                'quantite'        => ['required', 'numeric', 'min:0.01'],
                'prix_achat'      => ['required', 'numeric', 'min:0'],
                'date_peremption' => ['nullable', 'date'],
                'numero_lot'      => ['nullable', 'string', 'max:100'],
                'note'            => ['nullable', 'string', 'max:500'],
                'fichier'         => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            ],
            [
                'product_id.required' => 'Le produit est obligatoire.',
                'product_id.exists'   => 'Produit invalide.',
                'quantite.required'   => 'La quantité est obligatoire.',
                'quantite.min'        => 'La quantité doit être supérieure à 0.',
                'fichier.required'    => 'Un fichier joint (bon de livraison ou facture) est obligatoire.',
                'fichier.mimes'       => 'Le fichier doit être un PDF ou une image (jpg, jpeg, png).',
                'fichier.max'         => 'Le fichier ne doit pas dépasser 10 Mo.',
            ]
        );

        $mouvement = StockMovement::create([
            'product_id'      => $this->product_id,
            'fournisseur_id'  => $this->fournisseur_id ?: null,
            'quantite'        => $this->quantite,
            'prix_achat'      => $this->prix_achat ?: null,
            'date_peremption' => $this->date_peremption ?: null,
            'numero_lot'      => $this->numero_lot ?: null,
            'note'            => $this->note ?: null,
        ]);

        if ($this->fichier) {
            $path = $this->fichier->storeAs(
                "files/approvisionnements/{$mouvement->id}",
                $this->fichier->getClientOriginalName(),
                'local'
            );
            $mouvement->files()->create([
                'original_name' => $this->fichier->getClientOriginalName(),
                'path'          => $path,
                'mime_type'     => $this->fichier->getMimeType(),
                'size'          => $this->fichier->getSize(),
            ]);
        }

        $this->dispatch('approvisionnement-created');
        $this->reset();
        $this->closeModal();
    }
}
