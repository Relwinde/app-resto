<?php

namespace App\Livewire\Roles\Modals;

use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;

class CreateRole extends ModalComponent
{
    public string $name = '';

    public function render()
    {
        return view('livewire.roles.modals.create-role');
    }

    public function create(): void
    {
        Gate::authorize('Créer Rôle');

        $this->validate(
            ['name' => ['required', 'string', 'max:255', 'unique:roles,name']],
            [
                'name.required' => 'Le nom du rôle est obligatoire.',
                'name.unique'   => 'Ce rôle existe déjà.',
            ]
        );

        Role::create(['name' => $this->name, 'guard_name' => 'web']);

        $this->dispatch('role-created');
        $this->reset();
        $this->closeModal();
    }
}
