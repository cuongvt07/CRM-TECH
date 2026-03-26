<?php
namespace App\Livewire\Layout;

use Livewire\Component;

use Illuminate\Support\Facades\Auth;

class Header extends Component
{
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        $this->redirect('/', navigate: true);
    }

    public function render()
    {
        return view('livewire.layout.header');
    }
}
