<?php

namespace App\Livewire\Warehouse;

use Livewire\Component;

class WarehouseHistory extends Component
{
    use \Livewire\WithPagination;

    public $fromDate;
    public $toDate;
    public $type = ''; // all, import, export

    public function mount()
    {
        $this->fromDate = now()->startOfMonth()->toDateString();
        $this->toDate = now()->toDateString();
    }

    public function deleteTransaction($id)
    {
        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $transaction = \App\Models\InventoryTransaction::findOrFail($id);
            $inventory = \App\Models\Inventory::where('product_id', $transaction->product_id)->first();

            if ($inventory) {
                // Hoàn tác tồn kho (Revert)
                if ($transaction->type === 'import') {
                    // Nếu là nhập, khi hủy phải trừ lại kho
                    $inventory->decrement('quantity', $transaction->quantity);
                } elseif ($transaction->type === 'export') {
                    // Nếu là xuất, khi hủy phải cộng lại kho
                    $inventory->increment('quantity', $transaction->quantity);
                }
            }

            $transaction->delete();
            \Illuminate\Support\Facades\DB::commit();
            $this->dispatch('notify', ['message' => 'Đã hủy giao dịch và hoàn tác tồn kho!', 'type' => 'success']);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            $this->dispatch('notify', ['message' => 'Lỗi: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    public function render()
    {
        $query = \App\Models\InventoryTransaction::with(['product.warehouse', 'creator'])
            ->whereIn('type', ['import', 'export'])
            ->when($this->type, function($q) {
                $q->where('type', $this->type);
            })
            ->when($this->fromDate, function($q) {
                $q->whereDate('transaction_date', '>=', $this->fromDate);
            })
            ->when($this->toDate, function($q) {
                $q->whereDate('transaction_date', '<=', $this->toDate);
            })
            ->latest('transaction_date')
            ->latest('id');

        return view('livewire.warehouse.warehouse-history', [
            'transactions' => $query->paginate(15)
        ])->layout('components.layouts.app');
    }
}
