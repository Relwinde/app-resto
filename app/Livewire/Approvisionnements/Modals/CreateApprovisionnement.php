<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\Approvisionnement;
use App\Models\Caisse;
use App\Models\Fournisseur;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class CreateApprovisionnement extends ModalComponent
{
    use WithFileUploads;

    public $fournisseur_id = '';
    public $caisse_id      = '';
    public $note           = '';
    public $fichier        = null;

    public array $lignes = [];

    public function mount(): void
    {
        if (! Caisse::sessionOuverte()) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->ajouterLigne();
    }

    public function render()
    {
        return view('livewire.approvisionnements.modals.create-approvisionnement', [
            'produits'     => Product::where('is_suppliable', true)->orderBy('name')->get(),
            'fournisseurs' => Fournisseur::orderBy('name')->get(),
            'caisses'      => Caisse::where('statut', 'active')->orderBy('nom')->get(),
        ]);
    }

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function ajouterLigne(): void
    {
        $this->lignes[] = [
            'product_id'      => '',
            'quantite'        => '',
            'prix_achat'      => '',
            'date_peremption' => '',
            'numero_lot'      => '',
        ];
    }

    public function retirerLigne(int $index): void
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
    }

    public function getMontantTotalProperty(): float
    {
        return round(
            collect($this->lignes)->sum(fn ($ligne) => (float) ($ligne['prix_achat'] ?: 0)),
            2
        );
    }

    public function create(): void
    {
        Gate::authorize('Créer Approvisionnement');

        if (! Caisse::sessionOuverte()) {
            $this->dispatch('notify', message: 'Aucune session de caisse ouverte.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->validate(
            [
                'fournisseur_id'               => ['nullable', 'exists:fournisseurs,id'],
                'caisse_id'                    => ['required', 'exists:caisses,id'],
                'note'                         => ['nullable', 'string', 'max:500'],
                'fichier'                      => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
                'lignes'                       => ['required', 'array', 'min:1'],
                'lignes.*.product_id'          => ['required', 'exists:products,id'],
                'lignes.*.quantite'            => ['required', 'numeric', 'min:0.01'],
                'lignes.*.prix_achat'          => ['required', 'numeric', 'min:0.01'],
                'lignes.*.date_peremption'     => ['nullable', 'date'],
                'lignes.*.numero_lot'          => ['nullable', 'string', 'max:100'],
            ],
            [
                'caisse_id.required'            => 'La caisse à débiter est obligatoire.',
                'fichier.required'               => 'Un fichier joint (bon de livraison ou facture) est obligatoire.',
                'fichier.mimes'                  => 'Le fichier doit être un PDF ou une image (jpg, jpeg, png).',
                'fichier.max'                    => 'Le fichier ne doit pas dépasser 10 Mo.',
                'lignes.required'                => 'Ajoutez au moins un produit.',
                'lignes.min'                     => 'Ajoutez au moins un produit.',
                'lignes.*.product_id.required'   => 'Le produit est obligatoire.',
                'lignes.*.product_id.exists'     => 'Produit invalide.',
                'lignes.*.quantite.required'     => 'La quantité est obligatoire.',
                'lignes.*.quantite.min'          => 'La quantité doit être supérieure à 0.',
                'lignes.*.prix_achat.required'   => 'Le prix total est obligatoire.',
                'lignes.*.prix_achat.min'        => 'Le prix total doit être supérieur à 0.',
            ]
        );

        // Vérification du solde caisse avant toute écriture
        $caisse       = Caisse::findOrFail($this->caisse_id);
        $montantTotal = $this->montantTotal;

        if ($montantTotal > 0 && $montantTotal > (float) $caisse->solde_actuel) {
            $this->addError(
                'caisse_id',
                'Solde insuffisant dans la caisse « ' . $caisse->nom . ' » ('
                    . number_format($caisse->solde_actuel, 0, ',', ' ') . ' FCFA disponible).'
            );
            return;
        }

        DB::transaction(function () use ($caisse, $montantTotal) {
            $approvisionnement = Approvisionnement::create([
                'numero'            => Approvisionnement::genererNumero(),
                'fournisseur_id'    => $this->fournisseur_id ?: null,
                'caisse_id'         => $this->caisse_id,
                'session_caisse_id' => $caisse->sessionActive()?->id,
                'user_id'           => auth()->id(),
                'montant_total'     => $montantTotal,
                'note'              => $this->note ?: null,
            ]);

            foreach ($this->lignes as $ligne) {
                StockMovement::create([
                    'approvisionnement_id' => $approvisionnement->id,
                    'product_id'           => $ligne['product_id'],
                    'fournisseur_id'       => $this->fournisseur_id ?: null,
                    'caisse_id'            => $this->caisse_id,
                    'quantite'             => $ligne['quantite'],
                    'prix_achat'           => $ligne['prix_achat'] ?: null,
                    'date_peremption'      => $ligne['date_peremption'] ?: null,
                    'numero_lot'           => $ligne['numero_lot'] ?: null,
                    'note'                 => $this->note ?: null,
                ]);
            }

            if ($this->fichier) {
                $path = $this->fichier->storeAs(
                    "files/approvisionnements/{$approvisionnement->id}",
                    $this->fichier->getClientOriginalName(),
                    'local'
                );
                $approvisionnement->files()->create([
                    'original_name' => $this->fichier->getClientOriginalName(),
                    'path'          => $path,
                    'mime_type'     => $this->fichier->getMimeType(),
                    'size'          => $this->fichier->getSize(),
                ]);
            }

            if ($montantTotal > 0) {
                $caisse->retirer($montantTotal, $approvisionnement->note, null, null, 'approvisionnement', $approvisionnement->id);
            }
        });

        $this->dispatch('approvisionnement-created');
        $this->reset();
        $this->closeModal();
    }
}
