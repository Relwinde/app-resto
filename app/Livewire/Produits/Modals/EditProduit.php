<?php

namespace App\Livewire\Produits\Modals;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class EditProduit extends ModalComponent
{
    public Product $produit;

    public string $name = '';
    public $category_id = '';
    public $prix_vente = '';
    public $prix_achat = '';
    public string $unite = '';
    public bool $is_suppliable = false;

    public function mount(): void
    {
        Gate::authorize('Modifier Produit');
        $this->name          = $this->produit->name;
        $this->category_id   = $this->produit->category_id;
        $this->prix_vente    = $this->produit->prix_vente;
        $this->prix_achat    = $this->produit->prix_achat;
        $this->unite         = $this->produit->unite;
        $this->is_suppliable = $this->produit->is_suppliable;
    }

    public function render()
    {
        return view('livewire.produits.modals.edit-produit', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function save(): void
    {
        Gate::authorize('Modifier Produit');

        $this->validate(
            [
                'name'         => ['required', 'string', 'max:255', Rule::unique('products', 'name')->ignore($this->produit->id)],
                'category_id'  => ['nullable', 'exists:categories,id'],
                'prix_vente'   => ['required', 'numeric', 'min:0'],
                'prix_achat'   => ['nullable', 'numeric', 'min:0'],
                'unite'        => ['required', 'string', 'max:50'],
                'is_suppliable' => ['boolean'],
            ],
            [
                'name.required'       => 'Le nom du produit est obligatoire.',
                'name.unique'         => 'Ce produit existe déjà.',
                'prix_vente.required' => 'Le prix de vente est obligatoire.',
                'prix_vente.numeric'  => 'Le prix de vente doit être un nombre.',
                'unite.required'      => "L'unité de mesure est obligatoire.",
            ]
        );

        $this->produit->update([
            'name'          => $this->name,
            'category_id'   => $this->category_id ?: null,
            'prix_vente'    => $this->prix_vente,
            'prix_achat'    => $this->prix_achat ?: null,
            'unite'         => $this->unite,
            'is_suppliable' => $this->is_suppliable,
        ]);

        $this->dispatch('produit-updated');
        $this->closeModal();
    }
}
