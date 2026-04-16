<?php

namespace App\Livewire\Production;

use Livewire\Component;

class ProductionPlanner extends Component
{
    public $productId;
    public $quantity = 1;
    public $assignedTo;
    public $startDate;
    public $endDate;
    public $note;
    public $materialCheck = null;

    public function searchProduct($id)
    {
        $this->productId = $id;
        $this->checkMaterials();
    }

    public function updatedQuantity()
    {
        $this->checkMaterials();
    }

    public function checkMaterials()
    {
        if (!$this->productId || !$this->quantity) {
            $this->materialCheck = null;
            return;
        }

        $dummyPO = new \App\Models\ProductionOrder([
            'product_id' => $this->productId,
            'quantity' => $this->quantity
        ]);

        $this->materialCheck = $dummyPO->getMaterialStatus();
    }

    public function save()
    {
        $this->validate([
            'productId' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $product = \App\Models\Product::findOrFail($this->productId);
        if ($product->bom_status !== 'approved') {
            $this->dispatch('notify', ['message' => 'LỖI: Sản phẩm này chưa được QA phê duyệt BOM (Đã chấp nhận). Không thể phát lệnh!', 'type' => 'error']);
            return;
        }

        \App\Models\ProductionOrder::create([
            'product_id' => $this->productId,
            'quantity' => $this->quantity,
            'assigned_to' => $this->assignedTo,
            'start_date' => $this->startDate ?: now(),
            'end_date' => $this->endDate,
            'note' => $this->note,
            'status' => 'pending',
        ]);

        $this->dispatch('notify', ['message' => 'Đã tạo kế hoạch sản xuất!', 'type' => 'success']);
        return redirect()->route('production.index');
    }

    public function render()
    {
        return view('livewire.production.production-planner', [
            'products' => \App\Models\Product::whereHas('boms')->get(),
            'employees' => \App\Models\User::all(),
        ])->layout('components.layouts.app');
    }
}
