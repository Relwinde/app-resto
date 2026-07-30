<?php

namespace App\Livewire\Pertes\Modals;

use App\Models\Perte;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class CreatePerte extends ModalComponent
{
    use WithFileUploads;

    public $motif   = '';
    public $note    = '';
    public $fichier = null;

    public array $lignes = [];

    public array $motifs = [
        'perime' => 'Périmé',
        'casse'  => 'Casse / destruction accidentelle',
        'vol'    => 'Vol',
        'autre'  => 'Autre',
    ];

    public function mount(): void
    {
        $this->ajouterLigne();
    }

    public function render()
    {
        return view('livewire.pertes.modals.create-perte', [
            'produits' => Product::where('is_suppliable', true)->orderBy('name')->get(),
        ]);
    }

    public function removeFile(): void
    {
        $this->fichier = null;
    }

    public function ajouterLigne(): void
    {
        $this->lignes[] = [
            'product_id' => '',
            'quantite'   => '',
            'note'       => '',
        ];
    }

    public function retirerLigne(int $index): void
    {
        unset($this->lignes[$index]);
        $this->lignes = array_values($this->lignes);
    }

    public function create(): void
    {
        Gate::authorize('Créer Perte');

        $this->validate(
            [
                'motif'                => ['required', 'in:perime,casse,vol,autre'],
                'note'                 => ['nullable', 'string', 'max:500'],
                'fichier'              => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
                'lignes'               => ['required', 'array', 'min:1'],
                'lignes.*.product_id'  => ['required', 'exists:products,id'],
                'lignes.*.quantite'    => ['required', 'numeric', 'min:0.01'],
                'lignes.*.note'        => ['nullable', 'string', 'max:255'],
            ],
            [
                'motif.required'              => 'Le motif est obligatoire.',
                'lignes.required'              => 'Ajoutez au moins un produit.',
                'lignes.min'                   => 'Ajoutez au moins un produit.',
                'lignes.*.product_id.required' => 'Le produit est obligatoire.',
                'lignes.*.product_id.exists'   => 'Produit invalide.',
                'lignes.*.quantite.required'   => 'La quantité perdue est obligatoire.',
                'lignes.*.quantite.min'        => 'La quantité doit être supérieure à 0.',
            ]
        );

        DB::transaction(function () {
            $perte = Perte::create([
                'numero'  => Perte::genererNumero(),
                'motif'   => $this->motif,
                'user_id' => auth()->id(),
                'note'    => $this->note ?: null,
            ]);

            foreach ($this->lignes as $ligne) {
                StockMovement::create([
                    'perte_id'   => $perte->id,
                    'product_id' => $ligne['product_id'],
                    'quantite'   => -abs((float) $ligne['quantite']),
                    'note'       => $ligne['note'] ?: null,
                ]);
            }

            if ($this->fichier) {
                $path = $this->fichier->storeAs(
                    "files/pertes/{$perte->id}",
                    $this->fichier->getClientOriginalName(),
                    'local'
                );
                $perte->files()->create([
                    'original_name' => $this->fichier->getClientOriginalName(),
                    'path'          => $path,
                    'mime_type'     => $this->fichier->getMimeType(),
                    'size'          => $this->fichier->getSize(),
                ]);
            }

            $perte->recalculerValeurEstimee();
        });

        $this->dispatch('perte-created');
        $this->reset();
        $this->closeModal();
    }
}
