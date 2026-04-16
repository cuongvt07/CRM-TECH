<?php

namespace App\Livewire\Production;

use Livewire\Component;

class ProductionExecution extends Component
{
    public function updateProgress($id, $status)
    {
        // Logic đơn giản: Cập nhật trạng thái sang QC hoặc Hoàn thành tùy luồng
        // Ở đây ta gọi lại updateStatus của ProductionList hoặc logic tương tự
        // Nhưng để decoupled, ta xử lý trực tiếp hoặc gọi Service
        
        $po = \App\Models\ProductionOrder::findOrFail($id);
        
        if ($status === 'qc') {
            $po->update(['status' => 'qc']);
            $this->dispatch('notify', ['message' => 'Đã gửi yêu cầu kiểm tra QC!', 'type' => 'success']);
        }
    }

    public function render()
    {
        $myOrders = \App\Models\ProductionOrder::with(['product', 'order'])
            ->where('assigned_to', \Illuminate\Support\Facades\Auth::id())
            ->whereIn('status', ['in_progress', 'qc'])
            ->latest()
            ->get();

        return view('livewire.production.production-execution', [
            'myOrders' => $myOrders
        ])->layout('components.layouts.app');
    }
}
