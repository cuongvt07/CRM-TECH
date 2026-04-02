<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class EmployeeCreate extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $phone = '';
    public string $role = 'employee';
    public ?int $department_id = null;
    public string $status = 'active';
    public string $hire_date = '';

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15|unique:users,phone',
            'email' => 'nullable|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,director,manager,supervisor,team_leader,employee',
            'department_id' => 'nullable|exists:departments,id',
            'status' => 'required|in:active,inactive,on_leave',
            'hire_date' => 'nullable|date',
        ];
    }

    protected $messages = [
        'name.required' => 'Họ tên là bắt buộc.',
        'phone.required' => 'Số điện thoại là bắt buộc.',
        'phone.unique' => 'Số điện thoại đã tồn tại.',
        'email.email' => 'Email không hợp lệ.',
        'email.unique' => 'Email đã tồn tại.',
        'password.required' => 'Mật khẩu là bắt buộc.',
        'password.min' => 'Mật khẩu tối thiểu 6 ký tự.',
        'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
    ];

    public function save()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email ?: null,
            'password' => Hash::make($this->password),
            'role' => $this->role,
            'department_id' => $this->department_id ?: null,
            'status' => $this->status,
            'hire_date' => $this->hire_date ?: null,
        ]);

        session()->flash('success', 'Thêm nhân viên thành công.');
        return $this->redirect(route('employees.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.employee.employee-create', [
            'departments' => \App\Models\Department::where('status', 'active')->get(),
        ])->layout('layouts.app', ['title' => 'Thêm nhân viên']);
    }
}
