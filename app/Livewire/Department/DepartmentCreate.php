<?php

namespace App\Livewire\Department;

use App\Models\Department;
use Livewire\Component;

class DepartmentCreate extends Component
{
    public string $name = '';
    public string $description = '';
    public string $duties = '';
    public ?int $head_id = null;
    public string $phone = '';
    public string $status = 'active';

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

        Department::create([
            'name' => $this->name,
            'description' => $this->description ?: null,
            'duties' => $this->duties ?: null,
            'head_id' => $this->head_id ?: null,
            'phone' => $this->phone ?: null,
            'status' => $this->status,
        ]);

        session()->flash('success', 'Thêm phòng ban thành công.');
        return $this->redirect(route('departments.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.department.department-create', [
            'users' => \App\Models\User::orderBy('name')->get(),
        ])->layout('layouts.app', ['title' => 'Thêm phòng ban']);
    }
}
