<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Customer;

class CustomerList extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $filterType = '';
    public $showModal = false;
    
    // Form fields
    public $name, $tax_code, $phone, $email, $address, $contact_person, $note, $image;
    public $type = 'customer';
    public $editingCustomerId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => '']
    ];

    public function render()
    {
        $query = Customer::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_code', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        $customers = $query->latest()->paginate(10);

        return view('livewire.customer.customer-list', [
            'customers' => $customers
        ]);
    }

    public function openCreateModal()
    {
        $this->reset(['name', 'tax_code', 'phone', 'email', 'address', 'contact_person', 'note', 'image', 'editingCustomerId']);
        $this->type = 'customer';
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'image' => 'nullable|image|max:1024', // Max 1MB
            'type' => 'required|in:customer,supplier,both',
        ]);

        $data = [
            'name' => $this->name,
            'tax_code' => $this->tax_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'contact_person' => $this->contact_person,
            'note' => $this->note,
            'type' => $this->type,
        ];

        if ($this->image) {
            $data['image'] = $this->image->store('customers', 'public');
        }

        Customer::create($data);

        $this->showModal = false;
        $this->dispatch('notify', 'Thêm khách hàng thành công!');
    }
}
