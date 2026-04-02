<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryList extends Component
{
    public function render()
    {
        $categories = Category::all();
        return view('livewire.category.category-list', compact('categories'));
    }

    public function delete($id)
    {
        Category::find($id)?->delete();
    }
}
