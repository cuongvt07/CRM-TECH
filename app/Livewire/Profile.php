<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class Profile extends Component
{
    #[Url]
    public string $tab = 'profile';

    public function mount()
    {
        // No need to assign to $this->user here if we use Auth::user() in render or computed
    }

    public function switchTab($tabName)
    {
        $this->tab = $tabName;
    }

    public function render()
    {
        $user = Auth::user()->load('department.head');
        
        return view('livewire.profile', [
            'user' => $user,
            'department' => $user->department,
        ])->layout('layouts.app', ['title' => 'Hồ sơ cá nhân']);
    }
}
