<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryCreate extends Component
{
    public $name;
    public $description;

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        return $this->redirect(route('categories.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.category.category-create');
    }
}
