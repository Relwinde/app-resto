<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\Caisse;
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class CreateApprovisionnement extends ModalComponent
{
    use WithFileUploads;

    public $product_id      = '';
    public $fournisseur_id  = '';
    public $caisse_id       = '';
    public $quantite        = '';
    public $prix_achat      = '';
    public $date_peremption = '';
    public $numero_lot      = '';
    public $note            = '';
    public $fichier         = null;

    public function mount(): void
    {
        $restaurantId = auth()->user()->restaurant_id;
        if (! Caisse::sessionOuverte($restaurantId)) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
        }
    }

    public function render()
    {
        $restaurantId = auth()->user()->restaurant_id;
        return view('livewire.approvisionnements.modals.create-approvisionnement', [
            'produits'     => Product::forRestaurant($restaurantId)->where('is_suppliable', true)->orderBy('name')->get(),
            'fournisseurs' => Fournisseur::forRestaurant($restaurantId)->orderBy('name')->get(),
            'caisses'      => Caisse::forRestaurant($restaurantId)->where('statut', 'active')->orderBy('nom')->get(),
        ]);
    }

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function create(): void
    {
        Gate::authorize('Créer Approvisionnement');

        $restaurantId = auth()->user()->restaurant_id;

        if (! Caisse::sessionOuverte($restaurantId)) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->validate(
            [
                'product_id'      => ['required', 'exists:products,id'],
                'fournisseur_id'  => ['nullable', 'exists:fournisseurs,id'],
                'caisse_id'       => ['nullable', 'exists:caisses,id'],
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

        $caisse  = null;
        $montant = 0;

        if ($this->caisse_id) {
            $caisse  = Caisse::forRestaurant($restaurantId)->findOrFail($this->caisse_id);
            $montant = round((float) $this->quantite * (float) $this->prix_achat, 2);

            if ($montant > 0 && $montant > (float) $caisse->solde_actuel) {
                $this->addError(
                    'caisse_id',
                    'Solde insuffisant dans la caisse « ' . $caisse->nom . ' » ('
                        . number_format($caisse->solde_actuel, 0, ',', ' ') . ' FCFA disponible).'
                );
                return;
            }
        }

        $mouvement = StockMovement::create([
            'restaurant_id'   => $restaurantId,
            'product_id'      => $this->product_id,
            'fournisseur_id'  => $this->fournisseur_id ?: null,
            'caisse_id'       => $this->caisse_id ?: null,
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

        if ($caisse && $montant > 0) {
            $caisse->retirer($montant, $mouvement->note, $mouvement->id);
        }

        $this->dispatch('approvisionnement-created');
        $this->reset();
        $this->closeModal();
    }
}
