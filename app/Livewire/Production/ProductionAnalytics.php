<?php

namespace App\Livewire\Production;

use Livewire\Component;

class ProductionAnalytics extends Component
{
    public $monthlyProduced = [];
    public $qcStats = [];
    public $statusStats = [];

    public function mount()
    {
        $this->gatherStats();
    }

    public function gatherStats()
    {
        // 1. Sản lượng hoàn thành theo 12 tháng
        $producedByMonth = \App\Models\ProductionOrder::where('status', 'completed')
            ->whereYear('actual_end_date', date('Y'))
            ->get()
            ->groupBy(fn($po) => $po->actual_end_date->format('m'))
            ->map(fn($group) => $group->sum('quantity'));

        $this->monthlyProduced = array_values(array_replace(array_fill(1, 12, 0), $producedByMonth->toArray()));

        // 2. Tỷ lệ QC Pass/Fail từ bảng QCReport (nếu có) hoặc giả định từ ProductionOrder
        // Giả sử lấy từ QCReport
        $qcPass = \App\Models\QCReport::where('result', 'pass')->count();
        $qcFail = \App\Models\QCReport::where('result', 'fail')->count();
        $this->qcStats = [$qcPass, $qcFail];

        // 3. Toàn bộ lệnh sản xuất theo trạng thái
        $counts = \App\Models\ProductionOrder::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');
        
        $this->statusStats = [
            $counts['pending'] ?? 0,
            $counts['in_progress'] ?? 0,
            $counts['qc'] ?? 0,
            $counts['completed'] ?? 0
        ];
    }

    public function render()
    {
        return view('livewire.production.production-analytics')->layout('components.layouts.app');
    }
}
