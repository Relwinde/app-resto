<?php

namespace App\Livewire\Commandes\Modals;

use App\Models\Commande;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class ShowCommande extends ModalComponent
{
    public Commande $commande;

    public function render()
    {
        Gate::authorize('Voir Détail Commande');
        $this->commande->loadMissing(['items.produit', 'caisse', 'user', 'sessionCaisse', 'mouvement']);
        return view('livewire.commandes.modals.show-commande');
    }
}
