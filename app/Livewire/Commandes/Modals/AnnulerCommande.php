<?php

namespace App\Livewire\Commandes\Modals;

use App\Models\Commande;
use LivewireUI\Modal\ModalComponent;

class AnnulerCommande extends ModalComponent
{
    public Commande $commande;
    public string   $motif = '';

    public function render()
    {
        return view('livewire.commandes.modals.annuler-commande');
    }

    public function annuler(): void
    {
        if (! $this->commande->estModifiable()) {
            $this->addError('motif', 'Cette commande ne peut plus être annulée.');
            return;
        }

        $this->validate(
            ['motif' => ['nullable', 'string', 'max:500']],
        );

        $this->commande->update([
            'statut' => 'annulee',
            'note'   => $this->motif ?: $this->commande->note,
        ]);

        $this->dispatch('commande-annulee');
        $this->closeModal();
    }
}
