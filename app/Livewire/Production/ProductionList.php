<?php

namespace App\Livewire\Production;

use App\Models\ProductionOrder;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProductionList extends Component
{
    public $activeTab = 'pending'; // pending, in_progress, qc, completed

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function updateStatus($id, $newStatus)
    {
        $po = ProductionOrder::with('order.items')->findOrFail($id);
        
        // Cảnh báo nếu bắt đầu sản xuất mà thiếu vật tư
        if ($newStatus === 'in_progress') {
            $matStatus = $po->getMaterialStatus();
            if ($matStatus['status'] === 'insufficient') {
                // Chúng ta vẫn cho phép làm, nhưng ghi log hoặc có thông báo đặc biệt ở bước này nếu cần
            }
        }

        DB::beginTransaction();
        try {
            $po->update(['status' => $newStatus]);

            if ($newStatus === 'completed') {
                $po->update(['actual_end_date' => now()]);
                
                // 1. Tự động Nhập kho thành phẩm
                $inventory = Inventory::firstOrCreate(
                    ['product_id' => $po->product_id],
                    ['quantity' => 0]
                );
                $inventory->increment('quantity', $po->quantity);

                // Ghi log giao dịch kho
                InventoryTransaction::create([
                    'product_id' => $po->product_id,
                    'type' => 'import',
                    'transaction_date' => now(),
                    'quantity' => $po->quantity,
                    'reference_type' => 'production_order',
                    'reference_id' => $po->id,
                    'note' => "Nhập kho từ Lệnh sản xuất #{$po->id} (Đơn hàng #{$po->order_id})",
                    'created_by' => Auth::id() ?? 1,
                ]);

                // 2. Tự động Khấu trừ nguyên vật liệu theo Định mức (BOM)
                $productWithBom = Product::with('boms.material')->findOrFail($po->product_id);
                
                foreach ($productWithBom->boms as $bom) {
                    $requiredQty = $bom->quantity * $po->quantity;
                    
                    // Cập nhật tồn kho vật tư
                    $matInventory = Inventory::firstOrCreate(
                        ['product_id' => $bom->material_id],
                        ['quantity' => 0]
                    );
                    $matInventory->decrement('quantity', $requiredQty);

                    // Ghi log giao dịch xuất kho nguyên liệu
                    InventoryTransaction::create([
                        'product_id' => $bom->material_id,
                        'type' => 'export',
                        'transaction_date' => now(),
                        'quantity' => $requiredQty,
                        'reference_type' => 'production_order',
                        'reference_id' => $po->id,
                        'note' => "Xuất kho nguyên liệu để sản xuất Lệnh #{$po->id} ({$productWithBom->name})",
                        'created_by' => Auth::id() ?? 1,
                    ]);
                }

                // 3. Kiểm tra đơn hàng liên quan để cập nhật trạng thái
                if ($po->order) {
                    $this->checkOrderReady($po->order);
                }
            }
            
            DB::commit();
            $this->dispatch('notify', ['message' => 'Lệnh sản xuất đã được cập nhật!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['message' => 'Lỗi: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    private function checkOrderReady($order)
    {
        // Kiểm tra xem tất cả items trong đơn hàng đã đủ stock chưa
        $allReady = true;
        foreach ($order->items as $item) {
            $stock = $item->product->inventory ? $item->product->inventory->quantity : 0;
            if ($stock < $item->quantity) {
                $allReady = false;
                break;
            }
        }

        if ($allReady) {
            $order->update(['status' => 'READY']);
            
            // Thông báo cho Sales/Admin
            \App\Models\AppNotification::create([
                'user_id' => $order->created_by,
                'type' => 'ORDER_STATUS_CHANGED',
                'title' => "Đơn hàng #{$order->id} sẵn sàng!",
                'message' => "Tất cả sản phẩm đã được sản xuất đủ. Trạng thái: READY.",
                'reference_type' => 'Order',
                'reference_id' => $order->id,
            ]);
        }
    }

    public function render()
    {
        $productionOrders = ProductionOrder::with(['product', 'order', 'assignee'])
            ->where('status', $this->activeTab)
            ->latest()
            ->get();

        return view('livewire.production.production-list', [
            'productionOrders' => $productionOrders
        ])->layout('components.layouts.app');
    }
}
