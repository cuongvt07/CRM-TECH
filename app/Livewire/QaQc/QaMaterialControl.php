<?php

namespace App\Livewire\QaQc;

use Livewire\Component;

class QaMaterialControl extends Component
{
    public $selectedProductId = null;
    public $searchMaterial = '';
    public $newMaterialId = '';
    public $newQuantity = 1;
    public $newUnit = '';

    protected $listeners = ['refresh' => '$refresh'];

    public function selectProduct($id)
    {
        $this->selectedProductId = $id;
        $this->reset(['searchMaterial', 'newMaterialId', 'newQuantity', 'newUnit']);
    }

    public function addMaterial()
    {
        $product = \App\Models\Product::findOrFail($this->selectedProductId);
        if ($product->bom_status === 'approved') return;

        $this->validate([
            'newMaterialId' => 'required|exists:products,id',
            'newQuantity' => 'required|numeric|min:0.001',
        ]);

        $material = \App\Models\Product::findOrFail($this->newMaterialId);

        \App\Models\Bom::updateOrCreate(
            ['product_id' => $this->selectedProductId, 'material_id' => $this->newMaterialId],
            ['quantity' => $this->newQuantity, 'unit' => $this->newUnit ?: $material->unit]
        );

        $this->reset(['newMaterialId', 'newQuantity', 'newUnit', 'searchMaterial']);
        $this->dispatch('notify', ['message' => 'Đã thêm vật tư vào định mức!', 'type' => 'success']);
    }

    public function removeMaterial($bomId)
    {
        $product = \App\Models\Product::findOrFail($this->selectedProductId);
        if ($product->bom_status === 'approved') return;

        \App\Models\Bom::destroy($bomId);
        $this->dispatch('notify', ['message' => 'Đã xóa vật tư!', 'type' => 'info']);
    }

    public function approveBom($productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $product->update([
            'bom_status' => 'approved',
            'bom_approved_at' => now(),
            'bom_approved_by' => \Illuminate\Support\Facades\Auth::id(),
        ]);
        
        $this->dispatch('notify', ['message' => 'BOM đã được CHẤP NHẬN và KHÓA dữ liệu!', 'type' => 'success']);
        
        return redirect()->route('products.bom', $productId);
    }

    public function unapproveBom($productId)
    {
        // Chỉ Admin mới được hủy duyệt
        if (\Illuminate\Support\Facades\Auth::user()->role !== 'admin') {
            $this->dispatch('notify', ['message' => 'Bạn không có quyền thực hiện thao tác này!', 'type' => 'error']);
            return;
        }

        $product = \App\Models\Product::findOrFail($productId);
        $product->update([
            'bom_status' => 'draft',
            'bom_approved_at' => null,
            'bom_approved_by' => null,
        ]);
        
        $this->dispatch('notify', ['message' => 'Đã HỦY DUYỆT và mở khóa cấu hình!', 'type' => 'warning']);
    }

    public $searchProduct = '';

    public function render()
    {
        $productsQuery = \App\Models\Product::with(['boms.material', 'category'])
            ->latest();

        if ($this->searchProduct) {
            $productsQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchProduct . '%')
                  ->orWhere('code', 'like', '%' . $this->searchProduct . '%');
            });
        }

        $products = $productsQuery->get();

        $selectedProduct = $this->selectedProductId ? \App\Models\Product::with('boms.material', 'category', 'bomApprover')->find($this->selectedProductId) : null;

        // Lọc vật tư chỉ thuộc danh mục "Nguyên vật liệu" hoặc "Vật Tư" (giả sử ID=2 hoặc dùng tên)
        $availableMaterials = [];
        if ($this->searchMaterial) {
            $availableMaterials = \App\Models\Product::whereIn('category_id', [2]) // 2: Vật Tư
                ->where('id', '!=', $this->selectedProductId)
                ->where(function($q) {
                    $q->where('name', 'like', '%' . $this->searchMaterial . '%')
                      ->orWhere('code', 'like', '%' . $this->searchMaterial . '%');
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.qa-qc.qa-material-control', [
            'products' => $products,
            'selectedProduct' => $selectedProduct,
            'availableMaterials' => $availableMaterials
        ])->layout('components.layouts.app');
    }
}
