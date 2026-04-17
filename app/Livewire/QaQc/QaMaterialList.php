<?php

namespace App\Livewire\QaQc;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\InventoryTransaction;

class QaMaterialList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterInspectionStatus = '';
    public $filterApprovalStatus = '';
    
    public $selectedItems = [];
    public $showCreateModal = false;
    public $newName = '';
    public $newUnit = 'Kg';
    
    public $showApprovalModal = false;
    public $selectedTransactionId;
    public $qa_status = 'approved';
    public $qa_note = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterInspectionStatus' => ['except' => ''],
        'filterApprovalStatus' => ['except' => ''],
    ];

    public function openCreateModal()
    {
        $this->newName = '';
        $this->newUnit = 'Kg';
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->newName = '';
        $this->newUnit = 'Kg';
    }

    public function saveMaterial()
    {
        $this->validate([
            'newName' => 'required|string|max:255',
        ]);

        // Tạo mã định danh NVL tự động (NVL00 + 4 số thứ tự)
        $lastProduct = \App\Models\Product::where('code', 'like', 'NVL00%')->latest()->first();
        $nextNumber = 1;
        if ($lastProduct) {
            $lastNumber = (int) str_replace('NVL00', '', $lastProduct->code);
            $nextNumber = $lastNumber + 1;
        }
        $newCode = 'NVL00' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        \App\Models\Product::create([
            'code' => $newCode,
            'name' => $this->newName,
            'unit' => $this->newUnit,
            'price' => 0,
            'min_stock' => 0,
            'warehouse_id' => 1, // ID kho RAW_MAT
            'status' => 'active',
        ]);

        $this->dispatch('notify', 'Đã thêm nguyên vật liệu mới: ' . $this->newName);
        $this->closeCreateModal();
    }

    public function deleteSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('notify', ['message' => 'Vui lòng chọn ít nhất một mục để xóa!', 'type' => 'warning']);
            return;
        }

        $count = count($this->selectedItems);
        // Xóa sản phẩm khỏi bảng Product (Sử dụng SoftDeletes nếu model hỗ trợ)
        \App\Models\Product::whereIn('id', $this->selectedItems)->delete();
        
        $this->selectedItems = [];
        $this->dispatch('notify', "Đã xóa thành công $count mục.");
    }

    public function printSelected()
    {
        if (empty($this->selectedItems)) {
            $this->dispatch('notify', ['message' => 'Vui lòng chọn ít nhất một mục để in!', 'type' => 'warning']);
            return;
        }
        $this->dispatch('notify', 'Đang chuẩn bị bản in cho ' . count($this->selectedItems) . ' mục đã chọn...');
    }

    public function startInspecting($productId)
    {
        $product = \App\Models\Product::with('latestImportTransaction')->find($productId);
        $trx = $product?->latestImportTransaction;
        if ($trx && $trx->qa_inspection_status === 'pending') {
            $trx->update([
                'qa_inspection_status' => 'inspecting',
                'qa_inspector_id' => auth()->id(),
            ]);
        }
    }

    public function openApprovalModal($productId)
    {
        $product = \App\Models\Product::with('latestImportTransaction')->find($productId);
        $trx = $product?->latestImportTransaction;
        if ($trx) {
            $this->selectedTransactionId = $trx->id;
            $this->qa_status = $trx->qa_status === 'pending' ? 'approved' : $trx->qa_status;
            $this->qa_note = $trx->qa_note;
            $this->showApprovalModal = true;
            
            if ($trx->qa_inspection_status === 'pending') {
                $trx->update([
                    'qa_inspection_status' => 'inspecting',
                    'qa_inspector_id' => auth()->id(),
                ]);
            }
        } else {
            $this->dispatch('notify', ['message' => 'Nguyên vật liệu này chưa có phiếu nhập kho để thẩm định!', 'type' => 'warning']);
        }
    }

    public function closeApprovalModal()
    {
        $this->showApprovalModal = false;
        $this->reset(['selectedTransactionId', 'qa_status', 'qa_note']);
    }

    public function saveApproval()
    {
        $this->validate([
            'qa_status' => 'required|in:approved,rejected,pending',
        ]);

        $transaction = \App\Models\InventoryTransaction::find($this->selectedTransactionId);
        if ($transaction) {
            $transaction->update([
                'qa_status' => $this->qa_status,
                'qa_note' => $this->qa_note,
                'qa_inspector_id' => auth()->id(),
            ]);
            $this->closeApprovalModal();
        }
    }

    public function render()
    {
        $query = \App\Models\Product::with(['warehouse', 'inventory', 'latestImportTransaction'])
            ->where('warehouse_id', 1); // ID kho RAW_MAT

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterInspectionStatus) {
            $query->whereHas('latestImportTransaction', function($q) {
                $q->where('qa_inspection_status', $this->filterInspectionStatus);
            });
        }

        if ($this->filterApprovalStatus) {
            $query->whereHas('latestImportTransaction', function($q) {
                $q->where('qa_status', $this->filterApprovalStatus);
            });
        }

        $products = $query->latest()->paginate(15);

        return view('livewire.qa-qc.qa-material-list', [
            'products' => $products,
        ]);
    }
}
