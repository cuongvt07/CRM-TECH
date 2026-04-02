<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public int $perPage = 10;
    public ?int $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id)
    {
        $this->confirmingDeleteId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmingDeleteId = null;
    }

    public function deleteDepartment()
    {
        if ($this->confirmingDeleteId) {
            $department = Department::findOrFail($this->confirmingDeleteId);
            
            // Check if department has users before deleting? 
            // Or use soft delete and let it be.
            // Requirement says: "mỗi phòng ban quản lý nhân viên của mình"
            
            $department->delete();
            $this->confirmingDeleteId = null;
            session()->flash('success', 'Đã xóa phòng ban thành công (có thể khôi phục).');
        }
    }

    public function render()
    {
        $departments = Department::query()
            ->withCount('users')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.department.department-list', [
            'departments' => $departments,
        ])->layout('layouts.app', ['title' => 'Quản lý phòng ban']);
    }
}
