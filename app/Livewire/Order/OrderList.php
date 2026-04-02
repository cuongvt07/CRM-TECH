<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Component;

class OrderList extends Component
{
    public function render()
    {
        $orders = Order::with('creator')->latest()->get();
        return view('livewire.order.order-list', compact('orders'));
    }

    public function advanceStatus($orderId)
    {
        $order = Order::with('items.product.inventory')->find($orderId);
        if (!$order) return;

        $newStatus = $order->status;

        if ($order->status === 'PENDING') {
            $insufficientStock = false;
            foreach ($order->items as $item) {
                $stock = $item->product->inventory ? $item->product->inventory->quantity : 0;
                if ($stock < $item->quantity) {
                    $insufficientStock = true;
                    break;
                }
            }

            if ($insufficientStock) {
                $newStatus = 'IN_PRODUCTION';
                
                // Tự động tạo lệnh sản xuất cho các mặt hàng thiếu
                foreach ($order->items as $item) {
                    $stock = $item->product->inventory ? $item->product->inventory->quantity : 0;
                    if ($stock < $item->quantity) {
                        $needed = $item->quantity - $stock;
                        \App\Models\ProductionOrder::create([
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'quantity' => $needed,
                            'status' => 'pending',
                            'note' => "Tự động tạo từ Đơn hàng #{$order->id}",
                        ]);
                    }
                }
            } else {
                $newStatus = 'CONFIRMED';
                // Đủ hàng -> Bắt đầu trừ kho
                foreach ($order->items as $item) {
                    $inv = \App\Models\Inventory::firstOrCreate(
                        ['product_id' => $item->product_id], 
                        ['quantity' => 0]
                    );
                    $inv->decrement('quantity', $item->quantity);
                }
            }
        } elseif ($order->status === 'CONFIRMED') {
            $newStatus = 'READY';
        } elseif ($order->status === 'IN_PRODUCTION') {
            $newStatus = 'READY';
        } elseif ($order->status === 'READY') {
            $newStatus = 'DELIVERED';
        }

        if ($newStatus !== $order->status) {
            $order->update(['status' => $newStatus]);
        }
    }

    public function forceStatus($orderId, $status)
    {
        $order = Order::find($orderId);
        if ($order && in_array($status, ['COMPLETED', 'CANCELLED'])) {
            $order->update(['status' => $status]);
        }
    }
}
