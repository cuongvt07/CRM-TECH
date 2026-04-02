<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo phòng ban mẫu
        $hr = \App\Models\Department::create([
            'code' => 'HR',
            'name' => 'Phòng Nhân sự',
            'description' => 'Quản lý nhân sự và đào tạo',
            'status' => 'active',
        ]);

        \App\Models\Department::create([
            'code' => 'SALE',
            'name' => 'Phòng Kinh doanh',
            'description' => 'Tìm kiếm khách hàng và bán hàng',
            'status' => 'active',
        ]);

        \App\Models\Department::create([
            'code' => 'PROD',
            'name' => 'Phòng Sản xuất',
            'description' => 'Sản xuất sản phẩm',
            'status' => 'active',
        ]);

        // Tạo Admin và gán vào phòng HR
        $admin = \App\Models\User::create([
            'code' => 'EMP0001',
            'name' => 'Admin',
            'email' => 'admin@erp.com',
            'phone' => '0901234567',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'admin',
            'department_id' => $hr->id,
        ]);

        // Gán Admin làm trưởng phòng HR
        $hr->update(['head_id' => $admin->id]);
    }
}
