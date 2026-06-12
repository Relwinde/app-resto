<?php

namespace App\Livewire\Caisses\Modals;

use App\Models\Depense;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class EditDepense extends ModalComponent
{
    public int    $depenseId   = 0;
    public string $motif       = '';
    public string $montant     = '';
    public string $beneficiaire = '';
    public string $note        = '';

    public function mount(int $depenseId): void
    {
        $depense = Depense::find($depenseId);

        if (! $depense || ! $depense->estEdite()) {
            $this->dispatch('notify', message: 'Ce bon ne peut pas être modifié dans son état actuel.', type: 'error');
            $this->closeModal();
            return;
        }

        $this->depenseId    = $depenseId;
        $this->motif        = $depense->motif;
        $this->montant      = (string) $depense->montant;
        $this->beneficiaire = $depense->beneficiaire ?? '';
        $this->note         = $depense->note ?? '';
    }

    public function update(): void
    {
        Gate::authorize('Modifier Dépense');

        $depense = Depense::findOrFail($this->depenseId);

        if (! $depense->estEdite()) {
            $this->dispatch('notify', message: 'Ce bon ne peut plus être modifié.', type: 'error');
            $this->closeModal();
            return;
        }

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

        $depense->update([
            'motif'        => $this->motif,
            'montant'      => $this->montant,
            'beneficiaire' => $this->beneficiaire ?: null,
            'note'         => $this->note ?: null,
        ]);

        $this->dispatch('depense-modifiee');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.caisses.modals.edit-depense');
    }
}
