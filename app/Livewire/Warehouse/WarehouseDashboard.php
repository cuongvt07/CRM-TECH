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
    
    #[\Livewire\Attributes\Url]
    public $filterWarehouse = '';
    
    public $selectedItems = []; // For inventory bulk selection
    public $selectedTransactions = []; // For history bulk selection
    
    // History Filters
    public $historyFromDate = '';
    public $historyToDate = '';
    public $historyType = ''; // '', 'import', 'export'

    public function mount()
    {
        // filterWarehouse will be auto-filled by #[Url] if present in request
    }

    public function exportExcel()
    {
        // Ở môi trường demo, chúng ta sẽ giả lập thông báo bắt đầu tải file
        $this->dispatch('notify', ['message' => 'Đang chuẩn bị dữ liệu và xuất file Excel...', 'type' => 'info']);
    }

    public function printStock()
    {
        // Kích hoạt lệnh in trình duyệt cho vùng dữ liệu
        $this->dispatch('print-window');
    }

    public $showNotifications = false;
    public $feedbackNotes = [];

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

    public function toggleSelectAll()
    {
        // Lấy danh sách item đang hiển thị dựa trên filter (tương tự như trong render)
        $inventoryQuery = Product::where('status', 'active');
        if ($this->filterWarehouse) {
            $inventoryQuery->where('warehouse_id', $this->filterWarehouse);
        }
        if ($this->search) {
            $inventoryQuery->where(function ($q) {
                $q->where('code', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%');
            });
        }
        
        $currentIds = $inventoryQuery->pluck('id')->map(fn($id) => 'prod-' . $id)->toArray();

        // Nếu tất cả item hiện tại đã có trong selectedItems, thì bỏ chọn hết
        if (count(array_intersect($currentIds, $this->selectedItems)) === count($currentIds)) {
            $this->selectedItems = array_diff($this->selectedItems, $currentIds);
        } else {
            // Ngược lại thì thêm những ID chưa có vào
            $this->selectedItems = array_unique(array_merge($this->selectedItems, $currentIds));
        }
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

    public function toggleNotifications()
    {
        $this->showNotifications = !$this->showNotifications;
    }

    public function confirmWarehouseStock($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $note = $this->feedbackNotes['Order-' . $orderId] ?? '';

        $order->update([
            'warehouse_status' => $status,
            'warehouse_note' => $note,
            'warehouse_confirmed_by' => auth()->id(),
            'warehouse_confirmed_at' => now(),
            'status' => $status === 'sufficient' ? 'CONFIRMED' : 'PENDING'
        ]);

        $this->sendAppNotification($order->created_by, 'Order', $order->id, $status, $note);
        
        unset($this->feedbackNotes['Order-' . $orderId]);
        $this->dispatch('notify', ['message' => "Đã phản hồi đơn hàng #{$orderId}", 'type' => 'success']);
    }

    public function confirmProductionRequest($productionOrderId, $status)
    {
        $po = ProductionOrder::findOrFail($productionOrderId);
        $note = $this->feedbackNotes['Production-' . $productionOrderId] ?? '';

        $po->update([
            'warehouse_status' => $status,
            'warehouse_note' => $note,
            'warehouse_confirmed_by' => auth()->id(),
            'warehouse_confirmed_at' => now(),
        ]);

        $targetUser = $po->assigned_to ?: 1; 
        $this->sendAppNotification($targetUser, 'ProductionOrder', $po->id, $status, $note);

        unset($this->feedbackNotes['Production-' . $productionOrderId]);
        $this->dispatch('notify', ['message' => "Đã phản hồi yêu cầu sản xuất #{$productionOrderId}", 'type' => 'success']);
    }

    private function sendAppNotification($userId, $refType, $refId, $status, $note)
    {
        $labels = [
            'sufficient' => 'Còn hàng',
            'insufficient' => 'Hết hàng',
            'pending_production' => 'Chờ sản xuất',
            'delivering' => 'Đang soạn hàng'
        ];
        $statusLabel = $labels[$status] ?? $status;
        $title = "Kho phản hồi: {$statusLabel}";
        $prefix = $refType === 'Order' ? "Đơn hàng #{$refId}" : "Lệnh SX #{$refId}";

        AppNotification::create([
            'user_id' => $userId,
            'type' => 'warehouse_confirmation',
            'title' => $title,
            'message' => "{$prefix} - Phản hồi: {$statusLabel}. Nội dung: " . ($note ?: 'Không có'),
            'reference_type' => $refType,
            'reference_id' => $refId,
            'is_read' => false,
        ]);
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

        // ===== Notifications =====
        $pendingOrders = Order::where('status', 'PENDING')
            ->whereIn('warehouse_status', ['pending', 'insufficient', 'delivering'])
            ->with(['items.product'])
            ->latest()
            ->get();

        $pendingProductionOrders = ProductionOrder::where('status', 'pending')
            ->where('warehouse_status', 'pending')
            ->with(['product', 'order'])
            ->latest()
            ->get();

        return view('livewire.warehouse.warehouse-dashboard', [
            'warehouses' => $warehouses,
            'inventoryItems' => $inventoryItems,
            'transactions' => $transactions,
            'pendingOrders' => $pendingOrders,
            'pendingProductionOrders' => $pendingProductionOrders,
            'totalPending' => $pendingOrders->count() + $pendingProductionOrders->count(),
        ]);
    }
}
