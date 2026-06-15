<?php

namespace App\Livewire;

use App\Models\Commande;
use App\Models\Product;
use App\Models\Caisse;
use Livewire\Component;

class Dashboard extends Component
{
    public $restaurantId;
    public $totalCommands = 0;
    public $totalProducts = 0;
    public $totalCaisses = 0;
    public $totalRevenue = 0;

    #[\Livewire\Attributes\On('restaurant-changed')]
    public function updateRestaurant($restaurantId)
    {
        $this->restaurantId = $restaurantId;
        $this->loadStats();
    }

    public function mount($restaurantId)
    {
        $this->restaurantId = $restaurantId;
        $this->loadStats();
    }

    private function loadStats()
    {
        $this->totalCommands = Commande::forRestaurant($this->restaurantId)->count();
        $this->totalProducts = Product::forRestaurant($this->restaurantId)->count();
        $this->totalCaisses = Caisse::forRestaurant($this->restaurantId)->count();
        $this->totalRevenue = Commande::forRestaurant($this->restaurantId)
            ->where('statut', 'payee')
            ->sum('montant_total');
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'totalCommands' => $this->totalCommands,
            'totalProducts' => $this->totalProducts,
            'totalCaisses' => $this->totalCaisses,
            'totalRevenue' => $this->totalRevenue,
        ])->layout('components.layouts.app', ['title' => 'Accueil']);
    }
}
