<?php

namespace App\Livewire\Utilisateurs\Modals;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Role;

class EditUtilisateur extends ModalComponent
{
    public User   $utilisateur;
    public string $name     = '';
    public string $email    = '';
    public string $password = '';
    public string $role     = '';

    public function mount(): void
    {
        Gate::authorize('Modifier Utilisateur');

        $this->name  = $this->utilisateur->name;
        $this->email = $this->utilisateur->email;
        $this->role  = $this->utilisateur->roles->first()?->name ?? '';
    }

    public function render()
    {
        return view('livewire.utilisateurs.modals.edit-utilisateur', [
            'roles' => Role::where('name', '!=', 'super-admin')->orderBy('name')->get(),
        ]);
    }

    public function save(): void
    {
        Gate::authorize('Modifier Utilisateur');

        $this->validate(
            [
                'name'     => ['required', 'string', 'max:255'],
                'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($this->utilisateur->id)],
                'password' => ['nullable', 'string', 'min:8'],
                'role'     => ['required', 'exists:roles,name'],
            ],
            [
                'name.required'  => 'Le nom est obligatoire.',
                'email.required' => 'L\'email est obligatoire.',
                'email.unique'   => 'Cet email est déjà utilisé.',
                'password.min'   => 'Le mot de passe doit comporter au moins 8 caractères.',
                'role.required'  => 'Le rôle est obligatoire.',
            ]
        );

        $data = ['name' => $this->name, 'email' => $this->email];
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        $this->utilisateur->update($data);
        $this->utilisateur->syncRoles([$this->role]);

        $this->dispatch('utilisateur-updated');
        $this->closeModal();
    }
}
