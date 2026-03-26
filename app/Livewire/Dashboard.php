<?php
namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $revenueData = [];
    public $revenueLabels = [];

    public function mount()
    {
        // Sử dụng data mẫu (Hardcode)
        $this->revenueLabels = [
            'Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 
            'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 
            'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'
        ];
        
        // Cố định số liệu từ T1 đến T12 (VNĐ) để giả lập đường biểu diễn tăng trưởng
        $this->revenueData = [
            12500000, 
            18200000, 
            15000000, 
            22000000, 
            25500000, 
            30000000, 
            28000000, 
            32000000, 
            29500000, 
            35000000, 
            40000000, 
            45000000
        ];
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('components.layouts.app');
    }
}
