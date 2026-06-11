<?php

namespace App\Livewire\Caisses;

use App\Models\Caisse;
use App\Models\Category;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Product;
use App\Models\SessionCaisse;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Caisses extends Component
{
    public array $panier     = [];  // [product_id => ['produit' => ..., 'quantite' => n, 'sous_total' => n]]
    public int   $categorieId = 0;
    public string $search    = '';

    public string $table_numero = '';
    public string $client_nom   = '';

    public function render()
    {
        Gate::authorize('Voir Caisse');

        $caisse        = Caisse::where('statut', 'active')->first();
        $sessionActive = $caisse?->sessionActive();

        $produits = Product::with('category')
            ->when($this->categorieId, fn ($q) => $q->where('category_id', $this->categorieId))
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->whereNotNull('prix_vente')
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('name')->get();

        $total = collect($this->panier)->sum('sous_total');

        $pageHeader = [
            'title'       => 'Caisse',
            'subtitle'    => $caisse?->nom ?? 'Aucune caisse active',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Caisse'],
            ],
        ];

        return view('livewire.caisses.caisse', compact(
            'caisse', 'sessionActive', 'produits', 'categories', 'total', 'pageHeader'
        ))->layout('components.layouts.app', ['title' => 'Caisse']);
    }

    public function ajouterProduit(int $id): void
    {
        $produit = Product::find($id);
        if (! $produit) {
            return;
        }

        if (isset($this->panier[$id])) {
            $this->panier[$id]['quantite']++;
            $this->panier[$id]['sous_total'] = $this->panier[$id]['quantite'] * $this->panier[$id]['prix_unitaire'];
        } else {
            $this->panier[$id] = [
                'product_id'    => $produit->id,
                'nom'           => $produit->name,
                'prix_unitaire' => (float) $produit->prix_vente,
                'quantite'      => 1,
                'sous_total'    => (float) $produit->prix_vente,
            ];
        }
    }

    public function incrementer(int $id): void
    {
        if (isset($this->panier[$id])) {
            $this->panier[$id]['quantite']++;
            $this->panier[$id]['sous_total'] = $this->panier[$id]['quantite'] * $this->panier[$id]['prix_unitaire'];
        }
    }

    public function decrementer(int $id): void
    {
        if (! isset($this->panier[$id])) {
            return;
        }
        if ($this->panier[$id]['quantite'] > 1) {
            $this->panier[$id]['quantite']--;
            $this->panier[$id]['sous_total'] = $this->panier[$id]['quantite'] * $this->panier[$id]['prix_unitaire'];
        } else {
            unset($this->panier[$id]);
        }
    }

    public function retirer(int $id): void
    {
        unset($this->panier[$id]);
    }

    public function vider(): void
    {
        $this->panier       = [];
        $this->table_numero = '';
        $this->client_nom   = '';
    }

    public function ouvrirPaiement(): void
    {
        Gate::authorize('Encaisser Commande');

        if (empty($this->panier)) {
            return;
        }

        $caisse        = Caisse::where('statut', 'active')->first();
        $sessionActive = $caisse?->sessionActive();

        $total = collect($this->panier)->sum('sous_total');

        $this->dispatch('openModal', [
            'component' => 'caisses.modals.payer-commande',
            'arguments' => [
                'panier'         => array_values($this->panier),
                'total'          => $total,
                'caisse_id'      => $caisse?->id,
                'session_id'     => $sessionActive?->id,
                'table_numero'   => $this->table_numero,
                'client_nom'     => $this->client_nom,
            ],
        ]);
    }

    #[On('commande-payee')]
    public function onCommandePayee(): void
    {
        $this->vider();
    }
}
