<?php

namespace App\Livewire\QaQc;

use Livewire\Component;

class QaReportList extends Component
{
    public function render()
    {
        $reports = \App\Models\ProductionOrder::with(['product', 'qaChecklists', 'qcReports'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(15);

        // Thống kê tổng quan
        $totalOrders = \App\Models\ProductionOrder::where('status', 'completed')->count();
        $totalPass = \App\Models\QCReport::where('result', 'pass')->count();
        $totalFail = \App\Models\QCReport::where('result', 'fail')->count();

        return view('livewire.qa-qc.qa-report-list', [
            'reports' => $reports,
            'stats' => [
                'total' => $totalOrders,
                'pass' => $totalPass,
                'fail' => $totalFail,
                'pass_rate' => $totalOrders > 0 ? round(($totalPass / $totalOrders) * 100) : 0
            ]
        ])->layout('components.layouts.app');
    }
}
