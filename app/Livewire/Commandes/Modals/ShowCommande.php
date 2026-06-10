<?php

namespace App\Livewire\Commandes\Modals;

use App\Models\Commande;
use LivewireUI\Modal\ModalComponent;

class ShowCommande extends ModalComponent
{
    public Commande $commande;

    public function render()
    {
        $this->commande->loadMissing(['items.produit', 'caisse', 'user', 'sessionCaisse', 'mouvement']);
        return view('livewire.commandes.modals.show-commande');
    }
}
