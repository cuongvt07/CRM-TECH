<?php

namespace App\Livewire\Warehouse;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WarehouseReport extends Component
{
    public function render()
    {
        return view('livewire.warehouse.warehouse-report', [
            'shipmentTrendData' => $this->getShipmentTrendData(),
            'topProductsData' => $this->getTopProductsData(),
        ]);
    }

    private function getShipmentTrendData()
    {
        $months = [];
        $quantities = [];
        $revenues = [];

        // Last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthName = $date->format('m/Y');
            $months[] = $monthName;

            $data = InventoryTransaction::where('type', 'export')
                ->whereYear('transaction_date', $date->year)
                ->whereMonth('transaction_date', $date->month)
                ->select(
                    DB::raw('SUM(quantity) as total_qty'),
                    DB::raw('SUM(quantity * unit_price) as total_revenue')
                )
                ->first();

            $quantities[] = (float)($data->total_qty ?? 0);
            $revenues[] = (float)($data->total_revenue ?? 0);
        }

        return [
            'labels' => $months,
            'quantities' => $quantities,
            'revenues' => $revenues,
        ];
    }

    private function getTopProductsData()
    {
        $now = Carbon::now();
        
        // Top 20 products by total transaction volume (Import + Export) in current month
        $topProducts = InventoryTransaction::whereYear('transaction_date', $now->year)
            ->whereMonth('transaction_date', $now->month)
            ->select('product_id', DB::raw('SUM(quantity) as total_vol'))
            ->groupBy('product_id')
            ->orderByDesc('total_vol')
            ->limit(20)
            ->with('product')
            ->get();

        $labels = [];
        $importData = [];
        $exportData = [];

        foreach ($topProducts as $item) {
            $labels[] = $item->product ? $item->product->name : 'N/A';
            
            // Get imports for this product
            $import = InventoryTransaction::where('product_id', $item->product_id)
                ->where('type', 'import')
                ->whereYear('transaction_date', $now->year)
                ->whereMonth('transaction_date', $now->month)
                ->sum('quantity');
                
            // Get exports for this product
            $export = InventoryTransaction::where('product_id', $item->product_id)
                ->where('type', 'export')
                ->whereYear('transaction_date', $now->year)
                ->whereMonth('transaction_date', $now->month)
                ->sum('quantity');

            $importData[] = (float)$import;
            $exportData[] = (float)$export;
        }

        return [
            'labels' => $labels,
            'imports' => $importData,
            'exports' => $exportData,
        ];
    }
}
