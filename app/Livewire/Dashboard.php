<?php
namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $revenueData = [];
    public $revenueLabels = [];
    public $statusData = [];
    public $statusLabels = [];
    
    public $todayRevenue = 0;
    public $newOrdersCount = 0;
    public $inProductionCount = 0;
    public $lowStockCount = 0;
    public $topStaff = [];

    public function mount()
    {
        // 1. KPI Cards
        $this->todayRevenue = \App\Models\Order::where('status', 'COMPLETED')
            ->whereDate('order_date', today())
            ->sum('total_amount');
            
        $this->newOrdersCount = \App\Models\Order::whereDate('created_at', today())->count();
        
        $this->inProductionCount = \App\Models\Order::where('status', 'IN_PRODUCTION')->count();
        
        $this->lowStockCount = \App\Models\Product::whereHas('inventory', function($q) {
            $q->whereColumn('quantity', '<', 'products.min_stock');
        })->orWhereDoesntHave('inventory')->count();

        // 2. Top Staff Performance
        $this->topStaff = \App\Models\User::select('users.name', 'users.avatar')
            ->selectRaw('SUM(orders.total_amount) as total_revenue')
            ->join('orders', 'users.id', '=', 'orders.created_by')
            ->where('orders.status', 'COMPLETED')
            ->groupBy('users.id', 'users.name', 'users.avatar')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // 2. Revenue Chart (This Year)
        $this->revenueLabels = [
            'T1', 'T2', 'T3', 'T4', 'T5', 'T6', 
            'T7', 'T8', 'T9', 'T10', 'T11', 'T12'
        ];
        
        $monthlyRevenue = \App\Models\Order::where('status', 'COMPLETED')
            ->whereYear('order_date', date('Y'))
            ->selectRaw('MONTH(order_date) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->all();

        $this->revenueData = array_map(function($month) use ($monthlyRevenue) {
            return $monthlyRevenue[$month] ?? 0;
        }, range(1, 12));

        // 3. Status Pie Chart
        $statusCounts = \App\Models\Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->all();

        $this->statusLabels = array_keys($statusCounts);
        $this->statusData = array_values($statusCounts);
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app');
    }
}
