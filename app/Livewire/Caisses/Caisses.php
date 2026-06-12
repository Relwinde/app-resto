<?php

namespace App\Livewire\Caisses;

use App\Models\Caisse;
use App\Models\Category;
use App\Models\Commande;
use App\Models\CommandeProduit;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;

class Caisses extends Component
{
    public string $vue         = 'commandes';
    public array  $panier      = [];
    public int    $categorieId = 0;
    public string $search      = '';
    public string $table_numero = '';
    public string $client_nom   = '';

    #[On('session-ouverte')]
    #[On('depense-payee')]
    #[On('commande-encaissee')]
    #[On('session-fermee')]
    public function render()
    {
        Gate::authorize('Voir Caisse');

        $caisseEspeces = Caisse::where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile  = Caisse::where('type', 'mobile_money')->where('statut', 'active')->first();
        $sessionActive = $caisseEspeces?->sessionActive();

        $commandes = Commande::with(['items.produit', 'user'])
            ->whereIn('statut', ['en_attente', 'en_preparation', 'servie', 'payee'])
            ->when($sessionActive, fn ($q) => $q->where('session_caisse_id', $sessionActive->id))
            ->orderBy('created_at', 'desc')
            ->get();

        $produits = Product::with('category')
            ->when($this->categorieId, fn ($q) => $q->where('category_id', $this->categorieId))
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->whereNotNull('prix_vente')
            ->orderBy('name')
            ->get();

        $categories = Category::orderBy('name')->get();
        $total      = collect($this->panier)->sum('sous_total');

        $pageHeader = [
            'title'       => 'Caisse',
            'subtitle'    => $caisseEspeces?->nom ?? 'Caisse',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('dashboard')],
                ['label' => 'Caisse'],
            ],
        ];

        return view('livewire.caisses.caisse', compact(
            'caisseEspeces', 'caisseMobile', 'sessionActive', 'commandes', 'produits', 'categories', 'total', 'pageHeader'
        ))->layout('components.layouts.app', ['title' => 'Caisse']);
    }

    // ── Panier ────────────────────────────────────────────────────────────────

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

    // ── Enregistrement commande ───────────────────────────────────────────────

    public function enregistrerCommande(): void
    {
        Gate::authorize('Enregistrer Commande');

        if (empty($this->panier)) {
            return;
        }

        $caisse        = Caisse::where('type', 'especes')->where('statut', 'active')->first();
        $sessionActive = $caisse?->sessionActive();
        $total         = collect($this->panier)->sum('sous_total');

        $commande = Commande::create([
            'numero'            => Commande::genererNumero(),
            'caisse_id'         => $caisse?->id,
            'session_caisse_id' => $sessionActive?->id,
            'user_id'           => auth()->id(),
            'table_numero'      => $this->table_numero ?: null,
            'client_nom'        => $this->client_nom ?: null,
            'statut'            => 'en_attente',
            'montant_total'     => $total,
        ]);

        foreach ($this->panier as $item) {
            CommandeProduit::create([
                'commande_id'   => $commande->id,
                'product_id'    => $item['product_id'],
                'quantite'      => $item['quantite'],
                'prix_unitaire' => $item['prix_unitaire'],
                'sous_total'    => $item['sous_total'],
            ]);
        }

        $this->vider();
        $this->vue = 'commandes';
        $this->dispatch('commande-enregistree');
    }

    // ── Gestion statuts ───────────────────────────────────────────────────────

    public function changerStatut(int $commandeId, string $statut): void
    {
        Gate::authorize('Changer Statut Commande');

        $transitions = [
            'en_attente'     => 'en_preparation',
            'en_preparation' => 'servie',
        ];

        $commande = Commande::findOrFail($commandeId);

        if (! isset($transitions[$commande->statut]) || $transitions[$commande->statut] !== $statut) {
            return;
        }

        $commande->update(['statut' => $statut]);
    }

    // ── Event listeners ───────────────────────────────────────────────────────

    #[On('commande-enregistree')]
    #[On('commande-statut-change')]
    #[On('commande-encaissee')]
    #[On('commande-annulee')]
    public function onRefresh(): void {}
}
