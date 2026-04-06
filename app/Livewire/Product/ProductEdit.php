<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ProductEdit extends Component
{
    use WithFileUploads;

    public Product $product;
    public $code;
    public $name;
    public $description;
    public $unit;
    public $price;
    public $min_stock;
    public $max_stock;
    public $category_id;
    public $warehouse_id;
    public $brand;
    public $location;
    public $status;
    public $image;
    public $existing_image;

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->code = $product->code;
        $this->name = $product->name;
        $this->description = $product->description;
        $this->unit = $product->unit;
        $this->price = $product->price;
        $this->min_stock = $product->min_stock;
        $this->max_stock = $product->max_stock;
        $this->category_id = $product->category_id;
        $this->warehouse_id = $product->warehouse_id;
        $this->brand = $product->brand;
        $this->location = $product->location;
        $this->status = $product->status;
        $this->existing_image = $product->image_path;
    }

    public function save()
    {
        $this->validate([
            'code' => 'required|string|max:50|unique:products,code,' . $this->product->id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'required|string|max:20',
            'price' => 'required|numeric|min:0',
            'min_stock' => 'required|integer|min:0',
            'max_stock' => 'nullable|integer|gt:min_stock',
            'category_id' => 'nullable|exists:categories,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'brand' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|max:2048', // max 2MB
        ]);

        $imagePath = $this->product->image_path;
        if ($this->image) {
            // Delete old image if exists
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $this->image->store('products', 'public');
        }

        $this->product->update([
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
        return view('livewire.product.product-edit', [
            'categories' => Category::all(),
            'warehouses' => \App\Models\Warehouse::all(),
        ]);
    }
}
