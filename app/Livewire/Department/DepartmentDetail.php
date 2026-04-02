<?php

namespace App\Livewire\Department;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class DepartmentDetail extends Component
{
    use WithPagination;

    public Department $department;
    public string $search = '';
    public int $perPage = 10;

    public function mount(Department $department)
    {
        $this->department = $department;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $employees = $this->department->users()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('name')
            ->paginate($this->perPage);

        return view('livewire.department.department-detail', [
            'employees' => $employees,
        ])->layout('layouts.app', ['title' => 'Chi tiết phòng ban']);
    }
}
