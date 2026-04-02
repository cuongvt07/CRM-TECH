<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Livewire\Component;

class DepartmentEdit extends Component
{
    public Department $department;
    public string $code = '';
    public string $name = '';
    public string $description = '';
    public string $duties = '';
    public ?int $head_id = null;
    public string $phone = '';
    public string $status = 'active';

    public function mount(Department $department)
    {
        $this->department = $department;
        $this->code = $department->code;
        $this->name = $department->name;
        $this->description = $department->description ?? '';
        $this->duties = $department->duties ?? '';
        $this->head_id = $department->head_id;
        $this->phone = $department->phone ?? '';
        $this->status = $department->status;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duties' => 'nullable|string',
            'head_id' => 'nullable|exists:users,id',
            'phone' => 'nullable|string|max:15',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'name.required' => 'Tên phòng ban là bắt buộc.',
    ];

    public function save()
    {
        $this->validate();

        $this->department->update([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'duties' => $this->duties ?: null,
            'head_id' => $this->head_id ?: null,
            'phone' => $this->phone ?: null,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Cập nhật phòng ban thành công.');
        return $this->redirect(route('departments.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.department.department-edit', [
            'users' => $this->department->users()->orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Sửa phòng ban']);
    }
}
