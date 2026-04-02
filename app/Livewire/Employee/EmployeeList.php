<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRole = '';
    public string $filterStatus = '';
    public int $perPage = 10;
    public ?int $confirmingDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterRole' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterRole()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
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

    public function deleteEmployee()
    {
        if ($this->confirmingDeleteId) {
            $user = User::findOrFail($this->confirmingDeleteId);
            $user->delete();
            $this->confirmingDeleteId = null;
            session()->flash('success', 'Đã xóa nhân viên thành công (có thể khôi phục).');
        }
    }

    public function render()
    {
        $employees = User::query()
            ->with('department')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterRole, fn($q) => $q->where('role', $this->filterRole))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.employee.employee-list', [
            'employees' => $employees,
        ])->layout('layouts.app', ['title' => 'Quản lý nhân viên']);
    }
}
