<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryEdit extends Component
{
    public Category $category;
    public $name;
    public $description;

    public function mount(Category $category)
    {
        $this->category = $category;
        $this->name = $category->name;
        $this->description = $category->description;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $this->category->update([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        return $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.category.category-edit');
    }
}
