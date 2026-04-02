<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Livewire\Component;

class EmployeeDetail extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.employee.employee-detail')
            ->layout('layouts.app', ['title' => 'Chi tiết nhân viên']);
    }
}
