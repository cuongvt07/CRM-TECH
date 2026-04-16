<?php

namespace App\Livewire\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\AppNotification;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OrderCreate extends Component
{
    public $customer_id;
    public $customer_name;
    public $customer_phone;
    public $customer_address;
    public $note;

    // Cart items
    public $cart = [];

    public function mount()
    {
        $this->cart[] = ['product_id' => '', 'quantity' => 1];
    }

    public function addCartItem()
    {
        $this->cart[] = ['product_id' => '', 'quantity' => 1];
    }

    public function removeCartItem($index)
    {
        unset($this->cart[$index]);
        $this->cart = array_values($this->cart);
    }

    public function updatedCustomerId($value)
    {
        if ($value) {
            $customer = Customer::find($value);
            if ($customer) {
                $this->customer_name = $customer->name;
                $this->customer_phone = $customer->phone;
            }
        } else {
            $this->customer_name = '';
            $this->customer_phone = '';
        }
    }

    public function save()
    {
        $this->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:15',
            'cart' => 'required|array|min:1',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // Check inventory
            $insufficientStock = false;
            $itemsData = [];
            $totalAmount = 0;

            foreach ($this->cart as $item) {
                $product = Product::with('inventory')->findOrFail($item['product_id']);
                $stock = $product->inventory ? $product->inventory->quantity : 0;
                $quantity = (int)$item['quantity'];

                if ($stock < $quantity) {
                    $insufficientStock = true;
                }

                $subtotal = $product->price * $quantity;
                $totalAmount += $subtotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ];
            }

            // Create Order
            $order = Order::create([
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'note' => $this->note,
                'status' => $insufficientStock ? 'IN_PRODUCTION' : 'CONFIRMED', 
                'total_amount' => $totalAmount,
                'created_by' => Auth::id() ?? 1, 
                'order_date' => now()->toDateString(),
            ]);

            // Add Order Items & Auto Production
            foreach ($itemsData as $data) {
                $order->items()->create($data);

                $product = Product::with('inventory')->find($data['product_id']);
                $stock = $product->inventory ? $product->inventory->quantity : 0;

                if ($stock < $data['quantity']) {
                    // Tự động tạo Lệnh Sản xuất cho phần còn thiếu (hoặc toàn bộ tùy business rule)
                    // Ở đây tạo cho toàn bộ số lượng item trong đơn
                    \App\Models\ProductionOrder::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product_id'],
                        'quantity' => $data['quantity'],
                        'status' => 'pending',
                        'note' => "Tự động tạo từ Đơn hàng #{$order->id}",
                        'end_date' => now()->addDays(3), // Mặc định 3 ngày
                    ]);
                } else {
                    // Khấu trừ kho nếu đủ hàng
                    $inventory = $product->inventory;
                    $inventory->decrement('quantity', $data['quantity']);
                }
            }

            // Notifications
            $adminUsers = User::where('role', 'admin')->get();
            $productionUsers = User::where('role', 'production')->get();

            if ($insufficientStock) {
                // Thông báo cho Admin & Bộ phận sản xuất
                $title = "Lệnh sản xuất mới (Tự động): Đơn #" . $order->id;
                $message = "Đơn hàng từ {$order->customer_name} thiếu hàng. Hệ thống đã tự tạo lệnh sản xuất.";
                
                foreach ($adminUsers->concat($productionUsers) as $user) {
                    AppNotification::create([
                        'user_id' => $user->id,
                        'type' => 'STOCK_WARNING',
                        'title' => $title,
                        'message' => $message,
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                    ]);
                }
            } else {
                // Notify admin & warehouse
                $title = "Đơn hàng mới #" . $order->id . " đã trừ kho";
                $message = "Đơn hàng từ {$order->customer_name} đã được xác nhận tự động. Vui lòng bộ phận kho tiến hành soạn hàng.";
                
                $notifiedUserIds = [];
                // Admin
                foreach ($adminUsers as $admin) {
                    AppNotification::create([
                        'user_id' => $admin->id,
                        'type' => 'ORDER_CREATED',
                        'title' => $title,
                        'message' => $message,
                        'reference_type' => 'Order',
                        'reference_id' => $order->id,
                    ]);
                    $notifiedUserIds[] = $admin->id;
                }
                
                // Warehouse (avoid duplicates if an admin is also warehouse)
                foreach ($warehouseUsers as $whUser) {
                    if (!in_array($whUser->id, $notifiedUserIds)) {
                        AppNotification::create([
                            'user_id' => $whUser->id,
                            'type' => 'ORDER_CREATED',
                            'title' => $title,
                            'message' => $message,
                            'reference_type' => 'Order',
                            'reference_id' => $order->id,
                        ]);
                    }
                }
            }

            DB::commit();
            return $this->redirect(route('orders.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('cart', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.order.order-create', [
            'products' => Product::with('inventory')->where('status', 'active')->get(),
            'customers' => Customer::orderBy('name')->get()
        ]);
    }
}
