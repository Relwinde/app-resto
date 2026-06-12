<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class CreateDepense extends ModalComponent
{
    public string $motif        = '';
    public string $montant      = '';
    public string $beneficiaire = '';
    public string $note         = '';

    public function create(): void
    {
        Gate::authorize('Créer Dépense');

        $this->validate([
            'motif'        => ['required', 'string', 'max:255'],
            'montant'      => ['required', 'numeric', 'min:1'],
            'beneficiaire' => ['nullable', 'string', 'max:255'],
            'note'         => ['nullable', 'string', 'max:1000'],
        ], [
            'motif.required'   => 'Le motif est obligatoire.',
            'montant.required' => 'Le montant est obligatoire.',
            'montant.min'      => 'Le montant doit être supérieur à 0.',
        ]);

        Depense::create([
            'user_id'      => auth()->id(),
            'montant'      => $this->montant,
            'motif'        => $this->motif,
            'beneficiaire' => $this->beneficiaire ?: null,
            'note'         => $this->note ?: null,
            'statut'       => 'edite',
        ]);

        $this->dispatch('depense-created');
        $this->reset();
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.caisses.modals.create-depense');
    }
}
