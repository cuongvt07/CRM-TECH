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

    public function updateStatus($id, $newStatus)
    {
        $po = ProductionOrder::with('order.items')->findOrFail($id);
        $oldStatus = $po->status;

        if ($oldStatus === $newStatus) return;

        DB::beginTransaction();
        try {
            // 1. Logic Khấu trừ nguyên vật liệu khi BẮT ĐẦU (in_progress)
            if ($newStatus === 'in_progress' && $oldStatus === 'pending') {
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
                        'note' => "Xuất kho nguyên liệu BẮT ĐẦU sản xuất Lệnh #{$po->id} ({$productWithBom->name})",
                        'created_by' => Auth::id() ?? 1,
                    ]);
                }
            }

            // 2. Logic Nhập kho thành phẩm khi HOÀN TẤT (completed)
            if ($newStatus === 'completed') {
                $po->update(['actual_end_date' => now()]);
                
                // Tự động tạo bản ghi QC (mặc định là Pass khi kéo thả trực tiếp sang hoàn tất)
                \App\Models\QCReport::create([
                    'production_order_id' => $po->id,
                    'result' => 'pass',
                    'pass_quantity' => $po->quantity,
                    'fail_quantity' => 0,
                    'created_by' => Auth::id() ?? 1,
                ]);

                // Tự động Nhập kho thành phẩm
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
                    'note' => "Nhập kho hoàn tất từ Lệnh sản xuất #{$po->id}",
                    'created_by' => Auth::id() ?? 1,
                ]);

                // Kiểm tra đơn hàng liên quan để cập nhật trạng thái READY
                if ($po->order) {
                    $this->checkOrderReady($po->order);
                }
            }

            $po->update(['status' => $newStatus]);
            
            DB::commit();
            $this->dispatch('notify', ['message' => 'Lệnh sản xuất đã được cập nhật!', 'type' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', ['message' => 'Lỗi: ' . $e->getMessage(), 'type' => 'error']);
        }
    }

    private function checkOrderReady($order)
    {
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
        $orders = ProductionOrder::with(['product', 'order', 'assignee'])
            ->latest()
            ->get()
            ->groupBy('status');

        return view('livewire.production.production-list', [
            'ordersByStatus' => $orders
        ])->layout('components.layouts.app');
    }
}
