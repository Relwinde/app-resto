<?php

namespace App\Livewire\Utilisateurs\Modals;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;

class CreateUtilisateur extends ModalComponent
{
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $role     = '';

    public function render()
    {
        return view('livewire.utilisateurs.modals.create-utilisateur', [
            'roles' => Role::where('name', '!=', 'super-admin')->orderBy('name')->get(),
        ]);
    }

    public function create(): void
    {
        Gate::authorize('Créer Utilisateur');

        $restaurantId = auth()->user()->restaurant_id;

        $this->validate(
            [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
                'role'     => ['required', 'exists:roles,name'],
            ],
            [
                'name.required'     => 'Le nom est obligatoire.',
                'email.required'    => "L'email est obligatoire.",
                'email.unique'      => 'Cet email est déjà utilisé.',
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min'      => 'Le mot de passe doit comporter au moins 8 caractères.',
                'role.required'     => 'Le rôle est obligatoire.',
            ]
        );

        $user = User::create([
            'restaurant_id' => $restaurantId,
            'name'          => $this->name,
            'email'         => $this->email,
            'password'      => Hash::make($this->password),
        ]);

        $user->assignRole($this->role);

        $this->dispatch('utilisateur-created');
        $this->reset();
        $this->closeModal();
    }
}
