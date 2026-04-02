<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    public $phone = '0901234567';
    public $password = 'password';
    public $remember = false;

    public function login()
    {
        $this->validate([
            'phone' => 'required|string',
            'password' => 'required',
        ], [
            'phone.required' => 'Số điện thoại là bắt buộc.',
            'password.required' => 'Mật khẩu là bắt buộc.',
        ]);

        if (Auth::attempt(['phone' => $this->phone, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return $this->redirect('/', navigate: true);
        }

        session()->flash('error', 'Số điện thoại hoặc mật khẩu không chính xác.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
