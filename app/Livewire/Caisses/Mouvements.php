<?php

namespace App\Livewire\Caisses;

use App\Models\Caisse;
use App\Models\MouvementCaisse;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class Mouvements extends Component
{
    use WithPagination;

    public $restaurantId;
    public string $caisse_id = '';
    public string $type      = '';
    public string $dateMin   = '';
    public string $dateMax   = '';

    public function mount($restaurantId): void
    {
        $this->restaurantId = $restaurantId;
    }

    public function updatingCaisseId(): void { $this->resetPage(); }
    public function updatingType(): void { $this->resetPage(); }

    public function clear_filtres(): void
    {
        $this->caisse_id = '';
        $this->type      = '';
        $this->dateMin   = '';
        $this->dateMax   = '';
        $this->resetPage();
    }

    public function render()
    {
        Gate::authorize('Voir Journal Caisse');

        $caisse = $this->caisse_id
            ? Caisse::forRestaurant($this->restaurantId)->find($this->caisse_id)
            : Caisse::forRestaurant($this->restaurantId)->where('statut', 'active')->first();

        $mouvements = MouvementCaisse::with(['caisse', 'commande', 'user'])
            ->forRestaurant($this->restaurantId)
            ->when($this->caisse_id, fn ($q) => $q->where('caisse_id', $this->caisse_id))
            ->when($this->type, fn ($q) => $q->where('type', $this->type))
            ->when($this->dateMin, fn ($q) => $q->whereDate('created_at', '>=', $this->dateMin))
            ->when($this->dateMax, fn ($q) => $q->whereDate('created_at', '<=', $this->dateMax))
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $caisses       = Caisse::forRestaurant($this->restaurantId)->orderBy('nom')->get();
        $caisseEspeces = Caisse::forRestaurant($this->restaurantId)->where('type', 'especes')->where('statut', 'active')->first();
        $caisseMobile  = Caisse::forRestaurant($this->restaurantId)->where('type', 'mobile_money')->where('statut', 'active')->first();

        $pageHeader = [
            'title'       => 'Journal de caisse',
            'subtitle'    => 'Mouvements de caisse',
            'breadcrumbs' => [
                ['label' => 'Accueil', 'url' => route('app.dashboard', $this->restaurantId)],
                ['label' => 'Caisse', 'url' => route('app.caisse', $this->restaurantId)],
                ['label' => 'Journal'],
            ],
        ];

        return view('livewire.caisses.mouvements', compact('mouvements', 'caisses', 'caisse', 'caisseEspeces', 'caisseMobile', 'pageHeader'))
            ->layout('components.layouts.app', ['title' => 'Journal de caisse']);
    }
}
