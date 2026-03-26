<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    public $email = 'admin@erp.com';
    public $password = 'password';
    public $remember = false;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return $this->redirect('/', navigate: true);
        }

        session()->flash('error', 'Email hoặc mật khẩu không chính xác.');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
