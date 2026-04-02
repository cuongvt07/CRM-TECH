<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Profile;
use App\Livewire\Employee\EmployeeList;
use App\Livewire\Employee\EmployeeCreate;
use App\Livewire\Employee\EmployeeEdit;
use App\Livewire\Employee\EmployeeDetail;
use App\Livewire\Department\DepartmentList;
use App\Livewire\Department\DepartmentCreate;
use App\Livewire\Department\DepartmentEdit;
use App\Livewire\Department\DepartmentDetail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/login', Login::class)->name('login');

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/notifications', \App\Livewire\NotificationList::class)->name('notifications.index');

    // Employee CRUD
    Route::get('/employees', EmployeeList::class)->name('employees.index');
    Route::get('/employees/create', EmployeeCreate::class)->name('employees.create');
    Route::get('/employees/{user}/edit', EmployeeEdit::class)->name('employees.edit');
    Route::get('/employees/{user}', EmployeeDetail::class)->name('employees.show');

    // Department CRUD
    Route::get('/departments', DepartmentList::class)->name('departments.index');
    Route::get('/departments/create', DepartmentCreate::class)->name('departments.create');
    Route::get('/departments/{department}/edit', DepartmentEdit::class)->name('departments.edit');
    Route::get('/departments/{department}', DepartmentDetail::class)->name('departments.show');

    // Category CRUD
    Route::get('/categories', \App\Livewire\Category\CategoryList::class)->name('categories.index');
    Route::get('/categories/create', \App\Livewire\Category\CategoryCreate::class)->name('categories.create');
    Route::get('/categories/{category}/edit', \App\Livewire\Category\CategoryEdit::class)->name('categories.edit');

    // Product CRUD
    Route::get('/products', \App\Livewire\Product\ProductList::class)->name('products.index');
    Route::get('/products/create', \App\Livewire\Product\ProductCreate::class)->name('products.create');
    Route::get('/products/{product}/edit', \App\Livewire\Product\ProductEdit::class)->name('products.edit');
    Route::get('/products/{product}/bom', \App\Livewire\Product\ProductBom::class)->name('products.bom');

    // Customer CRUD
    Route::get('/customers', \App\Livewire\Customer\CustomerList::class)->name('customers.index');

    // Order CRUD
    Route::get('/orders', \App\Livewire\Order\OrderList::class)->name('orders.index');
    Route::get('/orders/create', \App\Livewire\Order\OrderCreate::class)->name('orders.create');
    Route::get('/sales/report', \App\Livewire\Order\SalesReport::class)->name('sales.report');

    // Warehouse
    Route::get('/warehouse', \App\Livewire\Warehouse\WarehouseDashboard::class)->name('warehouse.index');
    Route::get('/warehouse/transaction/{type}/{warehouse_code}/{productId?}', \App\Livewire\Warehouse\WarehouseTransactionCreate::class)->name('warehouse.transaction.create');
    Route::get('/warehouse/export', \App\Livewire\Warehouse\WarehouseExport::class)->name('warehouse.export');
    Route::get('/warehouse/report', \App\Livewire\Warehouse\WarehouseReport::class)->name('warehouse.report');

    // Production
    Route::get('/production', \App\Livewire\Production\ProductionList::class)->name('production.index');
});
