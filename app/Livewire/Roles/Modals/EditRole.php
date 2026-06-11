<?php

namespace App\Livewire\Roles\Modals;

use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EditRole extends ModalComponent
{
    public Role  $role;
    public array $selectedPermissions = [];

    public static function permissionGroups(): array
    {
        return [
            'Produits'           => ['Voir Produits', 'Créer Produit', 'Modifier Produit', 'Supprimer Produit'],
            'Catégories'         => ['Voir Catégories', 'Créer Catégorie', 'Modifier Catégorie', 'Supprimer Catégorie'],
            'Fournisseurs'       => ['Voir Fournisseurs', 'Créer Fournisseur', 'Modifier Fournisseur', 'Supprimer Fournisseur'],
            'Approvisionnements' => ['Voir Approvisionnements', 'Créer Approvisionnement', 'Modifier Approvisionnement', 'Supprimer Approvisionnement'],
            'Caisse'             => ['Voir Caisse', 'Ouvrir Session Caisse', 'Fermer Session Caisse', 'Encaisser Commande', 'Voir Sessions Caisse'],
            'Commandes'          => ['Voir Commandes', 'Voir Détail Commande', 'Annuler Commande'],
            'Journal'            => ['Voir Journal Caisse'],
            'Utilisateurs'       => ['Voir Utilisateurs', 'Créer Utilisateur', 'Modifier Utilisateur', 'Supprimer Utilisateur'],
            'Rôles'              => ['Voir Rôles', 'Créer Rôle', 'Modifier Rôle'],
        ];
    }

    public function mount(): void
    {
        Gate::authorize('Modifier Rôle');
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function render()
    {
        return view('livewire.roles.modals.edit-role', [
            'permissionGroups' => self::permissionGroups(),
        ]);
    }

    public function toggleAll(string $module): void
    {
        $modulePerms = self::permissionGroups()[$module] ?? [];
        $allSelected = count(array_intersect($modulePerms, $this->selectedPermissions)) === count($modulePerms);

        if ($allSelected) {
            $this->selectedPermissions = array_values(array_diff($this->selectedPermissions, $modulePerms));
        } else {
            $this->selectedPermissions = array_values(array_unique(array_merge($this->selectedPermissions, $modulePerms)));
        }
    }

    public function save(): void
    {
        Gate::authorize('Modifier Rôle');

        $valid = Permission::whereIn('name', $this->selectedPermissions)->pluck('name')->toArray();
        $this->role->syncPermissions($valid);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $this->dispatch('role-updated');
        $this->closeModal();
    }
}
