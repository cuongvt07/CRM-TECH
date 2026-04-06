<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;

    public $code;
    public $name;
    public $description;
    public $unit;
    public $price;
    public $min_stock;
    public $max_stock;
    public $category_id;
    public $warehouse_id;
    public $status = 'active';
    public $brand;
    public $location;
    public $image;

    public function save()
    {
        $this->validate([
            'code' => 'required|string|max:50|unique:products,code',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|gt:min_stock',
            'category_id' => 'nullable|exists:categories,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'status' => 'required|in:active,inactive',
            'brand' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $imagePath = null;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        Product::create([
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'price' => $this->price,
            'min_stock' => $this->min_stock,
            'max_stock' => $this->max_stock,
            'category_id' => $this->category_id,
            'warehouse_id' => $this->warehouse_id,
            'brand' => $this->brand,
            'location' => $this->location,
            'status' => $this->status,
            'image_path' => $imagePath,
        ]);

        return $this->redirect(route('products.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.product.product-create', [
            'categories' => Category::all(),
            'warehouses' => \App\Models\Warehouse::all(),
        ]);
    }
}
