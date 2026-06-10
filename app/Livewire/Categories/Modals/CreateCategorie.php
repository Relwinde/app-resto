<?php

namespace App\Livewire\Categories\Modals;

use App\Models\Category;
use LivewireUI\Modal\ModalComponent;

class CreateCategorie extends ModalComponent
{
    public string $name = '';

    public function render()
    {
        return view('livewire.categories.modals.create-categorie');
    }

    public function create(): void
    {
        $this->validate(
            ['name' => ['required', 'string', 'max:255', 'unique:categories,name']],
            [
                'name.required' => 'Le nom de la catégorie est obligatoire.',
                'name.unique'   => 'Cette catégorie existe déjà.',
            ]
        );

        Category::create(['name' => $this->name]);

        $this->dispatch('categorie-created');
        $this->reset();
        $this->closeModal();
    }
}
