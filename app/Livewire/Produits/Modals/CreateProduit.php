<?php

namespace App\Livewire\Produits\Modals;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use LivewireUI\Modal\ModalComponent;

class CreateProduit extends ModalComponent
{
    public string $name = '';
    public $category_id = '';
    public $prix_vente = '';
    public $prix_achat = '';
    public string $unite = '';
    public bool $is_suppliable = false;

    public function render()
    {
        return view('livewire.produits.modals.create-produit', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): void
    {
        Gate::authorize('Créer Produit');

        $this->validate(
            [
                'name'         => ['required', 'string', 'max:255', 'unique:products,name'],
                'category_id'  => ['nullable', 'exists:categories,id'],
                'prix_vente'   => ['required', 'numeric', 'min:0'],
                'prix_achat'   => ['nullable', 'numeric', 'min:0'],
                'unite'        => ['required', 'string', 'max:50'],
                'is_suppliable' => ['boolean'],
            ],
            [
                'name.required'      => 'Le nom du produit est obligatoire.',
                'name.unique'        => 'Ce produit existe déjà.',
                'prix_vente.required' => 'Le prix de vente est obligatoire.',
                'prix_vente.numeric' => 'Le prix de vente doit être un nombre.',
                'unite.required'     => "L'unité de mesure est obligatoire.",
            ]
        );

        Product::create([
            'name'          => $this->name,
            'category_id'   => $this->category_id ?: null,
            'prix_vente'    => $this->prix_vente,
            'prix_achat'    => $this->prix_achat ?: null,
            'unite'         => $this->unite,
            'is_suppliable' => $this->is_suppliable,
        ]);

        $this->dispatch('produit-created');
        $this->reset();
        $this->closeModal();
    }
}
