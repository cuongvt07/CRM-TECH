<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;

class ProductList extends Component
{
    public $search = '';
    public $category_filter = '';

    public function render()
    {
        $query = Product::with(['category', 'inventory']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->category_filter) {
            $query->where('category_id', $this->category_filter);
        }

        $products = $query->latest()->get();
        $categories = Category::all();

        return view('livewire.product.product-list', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function delete($id)
    {
        Product::find($id)?->delete();
    }
}
