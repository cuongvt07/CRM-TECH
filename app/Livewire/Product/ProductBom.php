<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Bom;
use App\Models\Category;
use App\Models\Warehouse;
use Livewire\Component;

class ProductBom extends Component
{
    public Product $product;
    public $materials = [];
    public $search = '';
    public $selectedMaterialId;
    public $quantity = 1;
    public $unit = '';

    public function mount(Product $product)
    {
        $this->product = $product->load('boms.material');
    }

    public function addMaterial()
    {
        if ($this->product->bom_status === 'approved') {
            $this->dispatch('notify', ['message' => 'Định mức này đã được QA duyệt và khóa. Không thể chỉnh sửa!', 'type' => 'error']);
            return;
        }

        $this->validate([
            'selectedMaterialId' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:0.01',
        ]);

        $material = Product::findOrFail($this->selectedMaterialId);

        Bom::updateOrCreate(
            ['product_id' => $this->product->id, 'material_id' => $material->id],
            ['quantity' => $this->quantity, 'unit' => $this->unit ?: $material->unit]
        );

        $this->product->load('boms.material');
        $this->reset(['selectedMaterialId', 'quantity', 'unit', 'search']);
        $this->dispatch('notify', ['message' => 'Đã thêm quy cách vật tư!', 'type' => 'success']);
    }

    public function removeMaterial($bomId)
    {
        if ($this->product->bom_status === 'approved') {
            $this->dispatch('notify', ['message' => 'Định mức đã bị khóa bởi QA!', 'type' => 'error']);
            return;
        }

        Bom::where('id', $bomId)->where('product_id', $this->product->id)->delete();
        $this->product->load('boms.material');
    }

    public function render()
    {
        // Only allow materials from Raw Materials or Supplies warehouses
        $availableMaterials = Product::whereIn('warehouse_id', function($q) {
                $q->select('id')->from('warehouses')->whereIn('code', ['RAW_MAT', 'SUPPLIES']);
            })
            ->where('status', 'active')
            ->where('id', '!=', $this->product->id)
            ->when($this->search, function($q) {
                $q->where(function($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->limit(10)
            ->get();

        return view('livewire.product.product-bom', [
            'availableMaterials' => $availableMaterials
        ])->layout('components.layouts.app');
    }
}
