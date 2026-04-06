<?php
namespace App\Livewire\Layout;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\ProductionOrder;
use App\Models\AppNotification;

class Header extends Component
{
    public $showNotificationSlide = false;
    public $activeTab = 'pending'; // 'pending' or 'history'
    public $feedbackNotes = [];
    public $panelWidth = 450; // Mặc định rộng 450px

    public function toggleNotificationSlide()
    {
        $this->showNotificationSlide = !$this->showNotificationSlide;
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        $this->redirect('/', navigate: true);
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

        $this->sendNotification($order->created_by, 'Order', $order->id, $status, $note);
        
        unset($this->feedbackNotes['Order-' . $orderId]);
        $this->showNotificationSlide = false;
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
        $this->sendNotification($targetUser, 'ProductionOrder', $po->id, $status, $note);

        unset($this->feedbackNotes['Production-' . $productionOrderId]);
        $this->showNotificationSlide = false;
        $this->dispatch('notify', ['message' => "Đã phản hồi yêu cầu sản xuất #{$productionOrderId}", 'type' => 'success']);
    }

    private function sendNotification($userId, $refType, $refId, $status, $note)
    {
        $labels = [
            'sufficient' => 'Đủ hàng',
            'insufficient' => 'THIẾU HÀNG',
            'pending_production' => 'Chờ sản xuất',
            'delivering' => 'Đang tiến hành giao'
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

    public function render()
    {
        $pendingOrders = Order::where('status', 'PENDING')
            ->where('warehouse_status', 'pending')
            ->latest()
            ->get();

        $pendingProductionOrders = ProductionOrder::where('status', 'pending')
            ->where('warehouse_status', 'pending')
            ->latest()
            ->get();

        $historyRequests = collect();
        if ($this->activeTab === 'history') {
            $sixtyDaysAgo = now()->subDays(60);
            $orderHistory = Order::whereNotNull('warehouse_confirmed_at')
                ->where('warehouse_confirmed_at', '>=', $sixtyDaysAgo)->get()->map(fn($i) => tap($i, fn($o) => $o->request_type = 'Order'));
            $productionHistory = ProductionOrder::whereNotNull('warehouse_confirmed_at')
                ->where('warehouse_confirmed_at', '>=', $sixtyDaysAgo)->get()->map(fn($i) => tap($i, fn($p) => $p->request_type = 'ProductionOrder'));
            $historyRequests = $orderHistory->concat($productionHistory)->sortByDesc('warehouse_confirmed_at');
        }

        return view('livewire.layout.header', [
            'pendingOrders' => $pendingOrders,
            'pendingProductionOrders' => $pendingProductionOrders,
            'totalPending' => $pendingOrders->count() + $pendingProductionOrders->count(),
            'historyRequests' => $historyRequests,
        ]);
    }
}
