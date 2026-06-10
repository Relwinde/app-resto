<?php

namespace App\Livewire\Approvisionnements\Modals;

use App\Models\StockMovement;
use LivewireUI\Modal\ModalComponent;

class ShowApprovisionnement extends ModalComponent
{
    public StockMovement $approvisionnement;

    public function render()
    {
        $this->approvisionnement->loadMissing('files');
        return view('livewire.approvisionnements.modals.show-approvisionnement');
    }
}
