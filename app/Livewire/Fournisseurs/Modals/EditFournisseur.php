<?php

namespace App\Livewire\Fournisseurs\Modals;

use App\Models\Fournisseur;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class EditFournisseur extends ModalComponent
{
    public Fournisseur $fournisseur;

    public string $name  = '';
    public string $phone = '';

    public function mount(): void
    {
        Gate::authorize('Modifier Fournisseur');
        $this->name  = $this->fournisseur->name;
        $this->phone = $this->fournisseur->phone ?? '';
    }

    public function render()
    {
        return view('livewire.fournisseurs.modals.edit-fournisseur');
    }

    public function save(): void
    {
        Gate::authorize('Modifier Fournisseur');

        $this->validate(
            [
                'name'  => ['required', 'string', 'max:255', Rule::unique('fournisseurs', 'name')->ignore($this->fournisseur->id)],
                'phone' => ['nullable', 'string', 'max:50'],
            ],
            [
                'name.required' => 'Le nom du fournisseur est obligatoire.',
                'name.unique'   => 'Ce fournisseur existe déjà.',
            ]
        );

        $this->fournisseur->update([
            'name'  => $this->name,
            'phone' => $this->phone ?: null,
        ]);

        $this->dispatch('fournisseur-updated');
        $this->closeModal();
    }
}
