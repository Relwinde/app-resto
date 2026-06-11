<?php

namespace App\Livewire\Categories\Modals;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use LivewireUI\Modal\ModalComponent;

class EditCategorie extends ModalComponent
{
    public Category $categorie;

    public string $name = '';

    public function mount(): void
    {
        Gate::authorize('Modifier Catégorie');
        $this->name = $this->categorie->name;
    }

    public function render()
    {
        return view('livewire.categories.modals.edit-categorie');
    }

    public function save(): void
    {
        Gate::authorize('Modifier Catégorie');

        $this->validate(
            ['name' => ['required', 'string', 'max:255', Rule::unique('categories', 'name')->ignore($this->categorie->id)]],
            [
                'name.required' => 'Le nom de la catégorie est obligatoire.',
                'name.unique'   => 'Cette catégorie existe déjà.',
            ]
        );

        $this->categorie->update(['name' => $this->name]);

        $this->dispatch('categorie-updated');
        $this->closeModal();
    }
}
