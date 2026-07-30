<?php

namespace App\Livewire\Pertes\Modals;

use App\Models\Perte;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditPerte extends ModalComponent
{
    public Perte $perte;

    public $motif = '';
    public $note  = '';

    public array $lignes = []; // [ ['id' => 12, 'nom_produit' => ..., 'unite' => ..., 'quantite' => ...], ... ]

    public array $motifs = [
        'perime' => 'Périmé',
        'casse'  => 'Casse / destruction accidentelle',
        'vol'    => 'Vol',
        'autre'  => 'Autre',
    ];

    public function mount(): void
    {
        Gate::authorize('Modifier Perte');

        $this->perte->loadMissing('lignes.product');

        $this->motif = $this->perte->motif;
        $this->note  = $this->perte->note;

        $this->lignes = $this->perte->lignes->map(fn (StockMovement $ligne) => [
            'id'          => $ligne->id,
            'nom_produit' => $ligne->product?->name,
            'unite'       => $ligne->product?->unite,
            'quantite'    => (string) abs((float) $ligne->quantite),
            'note'        => $ligne->note,
        ])->all();
    }

    public function render()
    {
        return view('livewire.pertes.modals.edit-perte');
    }

    public function save(): void
    {
        Gate::authorize('Modifier Perte');

        $this->validate(
            [
                'motif'             => ['required', 'in:perime,casse,vol,autre'],
                'note'              => ['nullable', 'string', 'max:500'],
                'lignes.*.quantite' => ['required', 'numeric', 'min:0.01'],
                'lignes.*.note'     => ['nullable', 'string', 'max:255'],
            ],
            [
                'motif.required'            => 'Le motif est obligatoire.',
                'lignes.*.quantite.required' => 'La quantité perdue est obligatoire.',
                'lignes.*.quantite.min'      => 'La quantité doit être supérieure à 0.',
            ]
        );

        foreach ($this->lignes as $ligne) {
            StockMovement::find($ligne['id'])?->update([
                'quantite' => -abs((float) $ligne['quantite']),
                'note'     => $ligne['note'] ?: null,
            ]);
        }

        $this->perte->update([
            'motif' => $this->motif,
            'note'  => $this->note ?: null,
        ]);

        $this->perte->recalculerValeurEstimee();

        $this->dispatch('perte-updated');
        $this->closeModal();
    }
}
