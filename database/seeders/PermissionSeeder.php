<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Produits
            'Voir Produits', 'Créer Produit', 'Modifier Produit', 'Supprimer Produit',
            // Catégories
            'Voir Catégories', 'Créer Catégorie', 'Modifier Catégorie', 'Supprimer Catégorie',
            // Fournisseurs
            'Voir Fournisseurs', 'Créer Fournisseur', 'Modifier Fournisseur', 'Supprimer Fournisseur',
            // Approvisionnements
            'Voir Approvisionnements', 'Créer Approvisionnement', 'Modifier Approvisionnement', 'Supprimer Approvisionnement',
            // Caisse
            'Voir Caisse', 'Ouvrir Session Caisse', 'Fermer Session Caisse',
            'Enregistrer Commande', 'Encaisser Commande', 'Voir Sessions Caisse',
            'Voir Activité Caisse', 'Changer Statut Commande', 'Enregistrer Dépôt',
            // Commandes
            'Voir Commandes', 'Voir Détail Commande', 'Annuler Commande',
            // Journal
            'Voir Journal Caisse',
            // Dépenses
            'Voir Dépenses', 'Créer Dépense', 'Modifier Dépense', 'Soumettre Dépense', 'Valider Dépense', 'Payer Dépense', 'Supprimer Dépense',
            // Utilisateurs
            'Voir Utilisateurs', 'Créer Utilisateur', 'Modifier Utilisateur', 'Supprimer Utilisateur',
            // Rôles
            'Voir Rôles', 'Créer Rôle', 'Modifier Rôle',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        // gérant : toutes les permissions
        $gerant = Role::firstOrCreate(['name' => 'gérant', 'guard_name' => 'web']);
        $gerant->syncPermissions($permissions);

        // caissier
        $caissier = Role::firstOrCreate(['name' => 'caissier', 'guard_name' => 'web']);
        $caissier->syncPermissions([
            'Voir Produits',
            'Voir Catégories',
            'Voir Caisse', 'Ouvrir Session Caisse', 'Fermer Session Caisse',
            'Enregistrer Commande', 'Encaisser Commande', 'Voir Sessions Caisse',
            'Voir Activité Caisse', 'Changer Statut Commande', 'Enregistrer Dépôt',
            'Voir Commandes', 'Voir Détail Commande', 'Annuler Commande',
            'Voir Journal Caisse',
            'Voir Dépenses', 'Créer Dépense', 'Modifier Dépense', 'Soumettre Dépense', 'Payer Dépense',
        ]);

        // magasinier
        $magasinier = Role::firstOrCreate(['name' => 'magasinier', 'guard_name' => 'web']);
        $magasinier->syncPermissions([
            'Voir Produits', 'Créer Produit', 'Modifier Produit', 'Supprimer Produit',
            'Voir Catégories', 'Créer Catégorie', 'Modifier Catégorie', 'Supprimer Catégorie',
            'Voir Fournisseurs', 'Créer Fournisseur', 'Modifier Fournisseur', 'Supprimer Fournisseur',
            'Voir Approvisionnements', 'Créer Approvisionnement', 'Modifier Approvisionnement', 'Supprimer Approvisionnement',
            'Voir Dépenses', 'Créer Dépense', 'Modifier Dépense', 'Soumettre Dépense',
        ]);
    }
}
