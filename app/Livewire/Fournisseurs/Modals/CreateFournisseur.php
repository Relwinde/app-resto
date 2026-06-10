<?php

namespace App\Livewire\Fournisseurs\Modals;

use App\Models\Fournisseur;
use LivewireUI\Modal\ModalComponent;

class CreateFournisseur extends ModalComponent
{
    public string $name  = '';
    public string $phone = '';

    public function render()
    {
        return view('livewire.fournisseurs.modals.create-fournisseur');
    }

    public function create(): void
    {
        $this->validate(
            [
                'name'  => ['required', 'string', 'max:255', 'unique:fournisseurs,name'],
                'phone' => ['nullable', 'string', 'max:50'],
            ],
            [
                'name.required' => 'Le nom du fournisseur est obligatoire.',
                'name.unique'   => 'Ce fournisseur existe déjà.',
            ]
        );

        Fournisseur::create([
            'name'  => $this->name,
            'phone' => $this->phone ?: null,
        ]);

        $this->dispatch('fournisseur-created');
        $this->reset();
        $this->closeModal();
    }
}
