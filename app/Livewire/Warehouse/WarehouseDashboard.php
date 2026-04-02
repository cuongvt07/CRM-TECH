<?php

namespace App\Livewire\Warehouse;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryTransaction;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\ProductionOrder;
use App\Models\AppNotification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WarehouseDashboard extends Component
{
    public $search = '';
    public $filterWarehouse = '';
    public $selectedItems = []; // For inventory bulk selection
    public $selectedTransactions = []; // For history bulk selection
    
    // History Filters
    public $historyFromDate = '';
    public $historyToDate = '';
    public $historyType = ''; // '', 'import', 'export'

    public function clearSelected()
    {
        $this->selectedItems = [];
    }

    public function clearHistoryFilters()
    {
        $this->historyFromDate = '';
        $this->historyToDate = '';
        $this->historyType = '';
    }

    public function clearSelectedTransactions()
    {
        $this->selectedTransactions = [];
    }

    // Cancel transaction
    public function cancelTransaction($transactionId)
    {
        $txn = InventoryTransaction::findOrFail($transactionId);
        $inventory = Inventory::where('product_id', $txn->product_id)->first();

        DB::beginTransaction();
        try {
            if ($inventory) {
                if ($txn->type === 'import') {
                    // Revert import → giảm tồn
                    $inventory->decrement('quantity', $txn->quantity);
                } else {
                    // Revert export → tăng tồn
                    $inventory->increment('quantity', $txn->quantity);
                }
            }
            $txn->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function getSelectedWarehouseCode()
    {
        if ($this->filterWarehouse) {
            $wh = Warehouse::find($this->filterWarehouse);
            return $wh?->code ?? 'RAW_MAT';
        }
        return 'RAW_MAT';
    }

    public function render()
    {
        $warehouses = Warehouse::all();

        // ===== Tồn kho =====
        $inventoryQuery = Product::with(['inventory', 'warehouse'])
            ->where('status', 'active');

        if ($this->filterWarehouse) {
            $inventoryQuery->where('warehouse_id', $this->filterWarehouse);
        }
        if ($this->search) {
            $inventoryQuery->where(function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }
        $inventoryItems = $inventoryQuery->get();

        // ===== Lịch sử Nhập/Xuất =====
        $txnQuery = InventoryTransaction::with(['product.warehouse', 'creator'])->latest();

        if ($this->filterWarehouse) {
            $txnQuery->whereHas('product', fn($q) => $q->where('warehouse_id', $this->filterWarehouse));
        }

        if ($this->historyType) {
            $txnQuery->where('type', $this->historyType);
        }

        if ($this->historyFromDate) {
            $txnQuery->whereDate('transaction_date', '>=', $this->historyFromDate);
        }

        if ($this->historyToDate) {
            $txnQuery->whereDate('transaction_date', '<=', $this->historyToDate);
        }
        if ($this->search) {
            $txnQuery->where(function ($q) {
                $q->where('partner_name', 'like', '%' . $this->search . '%')
                  ->orWhere('invoice_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('product', fn($p) => $p->where('name', 'like', '%' . $this->search . '%'));
            });
        }
        $transactions = $txnQuery->get();

        return view('livewire.warehouse.warehouse-dashboard', [
            'warehouses' => $warehouses,
            'inventoryItems' => $inventoryItems,
            'transactions' => $transactions,
        ]);
    }
}
