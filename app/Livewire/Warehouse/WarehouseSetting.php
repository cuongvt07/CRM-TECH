<?php

namespace App\Livewire\Warehouse;

use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Unit;
use Livewire\Component;

class WarehouseSetting extends Component
{
    public $activeTab = 'warehouses'; // warehouses, categories, units

    // Warehouse state
    public $warehouseId, $warehouseName, $warehouseCode, $warehouseDescription;
    
    // Category state
    public $categoryId, $categoryName, $categoryDescription;

    // Unit state
    public $unitId, $unitName, $unitDescription;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    // --- WAREHOUSE CRUD ---
    public function saveWarehouse()
    {
        $this->validate([
            'warehouseName' => 'required|string|max:255',
            'warehouseCode' => 'required|string|max:50|unique:warehouses,code,' . $this->warehouseId,
            'warehouseDescription' => 'nullable|string'
        ]);

        Warehouse::updateOrCreate(
            ['id' => $this->warehouseId],
            [
                'name' => $this->warehouseName,
                'code' => $this->warehouseCode,
                'description' => $this->warehouseDescription
            ]
        );

        $this->reset(['warehouseId', 'warehouseName', 'warehouseCode', 'warehouseDescription']);
        $this->dispatch('notify', ['message' => 'Lưu thông tin kho thành công!', 'type' => 'success']);
    }

    public function editWarehouse($id)
    {
        $wh = Warehouse::findOrFail($id);
        $this->warehouseId = $wh->id;
        $this->warehouseName = $wh->name;
        $this->warehouseCode = $wh->code;
        $this->warehouseDescription = $wh->description;
    }

    // --- CATEGORY CRUD ---
    public function saveCategory()
    {
        $this->validate([
            'categoryName' => 'required|string|max:255',
            'categoryDescription' => 'nullable|string'
        ]);

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->categoryName,
                'description' => $this->categoryDescription
            ]
        );

        $this->reset(['categoryId', 'categoryName', 'categoryDescription']);
        $this->dispatch('notify', ['message' => 'Lưu danh mục thành công!', 'type' => 'success']);
    }

    public function editCategory($id)
    {
        $cat = Category::findOrFail($id);
        $this->categoryId = $cat->id;
        $this->categoryName = $cat->name;
        $this->categoryDescription = $cat->description;
    }

    // --- UNIT CRUD ---
    public function saveUnit()
    {
        $this->validate([
            'unitName' => 'required|string|max:50|unique:units,name,' . $this->unitId,
            'unitDescription' => 'nullable|string'
        ]);

        Unit::updateOrCreate(
            ['id' => $this->unitId],
            [
                'name' => $this->unitName,
                'description' => $this->unitDescription
            ]
        );

        $this->reset(['unitId', 'unitName', 'unitDescription']);
        $this->dispatch('notify', ['message' => 'Lưu đơn vị tính thành công!', 'type' => 'success']);
    }

    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $this->unitId = $unit->id;
        $this->unitName = $unit->name;
        $this->unitDescription = $unit->description;
    }

    public function delete($type, $id)
    {
        if ($type === 'warehouse') Warehouse::find($id)?->delete();
        if ($type === 'category') Category::find($id)?->delete();
        if ($type === 'unit') Unit::find($id)?->delete();
        
        $this->dispatch('notify', ['message' => 'Đã xóa bản ghi!', 'type' => 'info']);
    }

    public function render()
    {
        return view('livewire.warehouse.warehouse-setting', [
            'warehouses' => Warehouse::all(),
            'categories' => Category::all(),
            'units' => Unit::all(),
        ])->layout('components.layouts.app');
    }
}
