<?php

namespace App\Livewire\QaQc;

use Livewire\Component;

class QaWorkPlan extends Component
{
    public $newTaskName = []; // [order_id => name]

    public function addTask($orderId)
    {
        if (empty($this->newTaskName[$orderId])) return;

        \App\Models\QaChecklist::create([
            'production_order_id' => $orderId,
            'task_name' => $this->newTaskName[$orderId],
            'is_completed' => false,
        ]);

        $this->newTaskName[$orderId] = '';
        $this->dispatch('notify', ['message' => 'Đã thêm đầu việc kiểm tra!', 'type' => 'success']);
    }

    public function toggleTask($taskId)
    {
        $task = \App\Models\QaChecklist::findOrFail($taskId);
        $task->update([
            'is_completed' => !$task->is_completed,
            'completed_at' => !$task->is_completed ? now() : null,
            'inspector_id' => !$task->is_completed ? \Illuminate\Support\Facades\Auth::id() ?? 1 : null,
        ]);
    }

    public function deleteTask($taskId)
    {
        \App\Models\QaChecklist::findOrFail($taskId)->delete();
        $this->dispatch('notify', ['message' => 'Đã xóa đầu việc.', 'type' => 'info']);
    }

    public function render()
    {
        $orders = \App\Models\ProductionOrder::with(['product', 'qaChecklists', 'assignee'])
            ->whereIn('status', ['in_progress', 'qc'])
            ->latest()
            ->get();

        return view('livewire.qa-qc.qa-work-plan', [
            'orders' => $orders
        ])->layout('components.layouts.app');
    }
}
