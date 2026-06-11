<?php

namespace App\Livewire\Roles;

use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class Roles extends Component
{
    #[On('role-created')]
    #[On('role-updated')]
    public function render()
    {
        Gate::authorize('Voir Rôles');

        $roles = Role::withCount('users')
            ->with('permissions')
            ->where('name', '!=', 'super-admin')
            ->orderBy('name')
            ->get();

        $pageHeader = [
            'title'       => 'Rôles',
            'subtitle'    => 'Gestion des rôles et permissions',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Rôles'],
            ],
        ];

        return view('livewire.roles.roles', compact('roles', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Rôles']);
    }
}
