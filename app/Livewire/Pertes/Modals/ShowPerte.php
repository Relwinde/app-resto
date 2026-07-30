<?php

namespace App\Livewire\Pertes\Modals;

use App\Models\Perte;
use LivewireUI\Modal\ModalComponent;

class ShowPerte extends ModalComponent
{
    public Perte $perte;

    public array $motifs = [
        'perime' => 'Périmé',
        'casse'  => 'Casse / destruction accidentelle',
        'vol'    => 'Vol',
        'autre'  => 'Autre',
    ];

    public function render()
    {
        $this->perte->loadMissing(['lignes.product', 'user', 'files']);
        return view('livewire.pertes.modals.show-perte');
    }
}
