<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\Approvisionnement;
use LivewireUI\Modal\ModalComponent;

class ShowApprovisionnement extends ModalComponent
{
    public Approvisionnement $approvisionnement;

    public function render()
    {
        $this->approvisionnement->loadMissing(['lignes.product', 'fournisseur', 'caisse', 'user', 'files']);
        return view('livewire.approvisionnements.modals.show-approvisionnement');
    }
}
