<?php

namespace App\Livewire\QaQc;

use Livewire\Component;
use App\Models\ProductionOrder;
use App\Models\QaChecklist;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;

class QaWorkPlan extends Component
{
    public $activeTab = 'incoming'; // incoming or production
    public $newTaskName = []; // [order_id => name]
    
    // Incoming Goods QC fields
    public $showIncomingModal = false;
    public $selectedTrxId;
    public $qa_status = 'approved';
    public $qa_note = '';

    protected $queryString = ['activeTab' => ['except' => 'incoming']];

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    // --- Production Checklist Methods ---
    public function addTask($orderId)
    {
        if (empty($this->newTaskName[$orderId])) return;

        QaChecklist::create([
            'production_order_id' => $orderId,
            'task_name' => $this->newTaskName[$orderId],
            'is_completed' => false,
        ]);

        $this->newTaskName[$orderId] = '';
        $this->dispatch('notify', 'Đã thêm đầu việc kiểm tra!');
    }

    public function toggleTask($taskId)
    {
        $task = QaChecklist::findOrFail($taskId);
        $task->update([
            'is_completed' => !$task->is_completed,
            'completed_at' => !$task->is_completed ? now() : null,
            'inspector_id' => !$task->is_completed ? Auth::id() : null,
        ]);
    }

    public function deleteTask($taskId)
    {
        QaChecklist::findOrFail($taskId)->delete();
        $this->dispatch('notify', 'Đã xóa đầu việc.');
    }

    // --- Incoming Goods QC Methods ---
    public function startInspecting($trxId)
    {
        $trx = InventoryTransaction::find($trxId);
        if ($trx && $trx->qa_inspection_status === 'pending') {
            $trx->update([
                'qa_inspection_status' => 'inspecting',
                'qa_inspector_id' => Auth::id(),
            ]);
        }
    }

    public function openIncomingModal($trxId)
    {
        $trx = InventoryTransaction::find($trxId);
        if ($trx) {
            $this->selectedTrxId = $trx->id;
            $this->qa_status = $trx->qa_status === 'pending' ? 'approved' : $trx->qa_status;
            $this->qa_note = $trx->qa_note;
            $this->showIncomingModal = true;
            
            if ($trx->qa_inspection_status === 'pending') {
                $trx->update(['qa_inspection_status' => 'inspecting', 'qa_inspector_id' => Auth::id()]);
            }
        }
    }

    public function closeIncomingModal()
    {
        $this->showIncomingModal = false;
        $this->reset(['selectedTrxId', 'qa_status', 'qa_note']);
    }

    public function saveIncomingApproval()
    {
        $trx = InventoryTransaction::find($this->selectedTrxId);
        if ($trx) {
            $trx->update([
                'qa_status' => $this->qa_status,
                'qa_note' => $this->qa_note,
                'qa_inspector_id' => Auth::id(),
            ]);
            $this->dispatch('notify', 'Đã lưu kết quả thẩm định lô hàng!');
            $this->closeIncomingModal();
        }
    }

    public function render()
    {
        // 1. Checklist Hàng Nhập (RMQC)
        $incomingTransactions = InventoryTransaction::with(['product'])
            ->where('type', 'import')
            ->whereHas('product.warehouse', function($q) {
                $q->where('code', 'RAW_MAT');
            })
            ->latest()
            ->get();

        // 2. Checklist Sản Xuất (IPQC)
        $productionOrders = ProductionOrder::with(['product', 'qaChecklists', 'assignee'])
            ->whereIn('status', ['in_progress', 'qc'])
            ->latest()
            ->get();

        return view('livewire.qa-qc.qa-work-plan', [
            'incomingTransactions' => $incomingTransactions,
            'orders' => $productionOrders
        ])->layout('components.layouts.app');
    }
}
