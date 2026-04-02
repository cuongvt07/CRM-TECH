<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lý nhân viên</h1>
            <p class="text-sm text-gray-500 mt-1">Danh sách toàn bộ nhân viên trong hệ thống</p>
        </div>
        <a wire:navigate href="{{ route('employees.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition-colors text-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm nhân viên
        </a>
    </div>

    {{-- Flash message --}}
    @if (session()->has('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2 animate-fade-in"
             x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="md:col-span-2 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Tìm theo tên, SĐT, mã NV..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" />
            </div>
            <select wire:model.live="filterRole"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white">
                <option value="">-- Tất cả vai trò --</option>
                <option value="admin">Admin</option>
                <option value="sales">Sales</option>
                <option value="production">Sản xuất</option>
                <option value="warehouse">Kho</option>
                <option value="hr">Nhân sự</option>
                <option value="viewer">Viewer</option>
            </select>
            <select wire:model.live="filterStatus"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="active">Đang làm việc</option>
                <option value="inactive">Nghỉ việc</option>
                <option value="on_leave">Nghỉ phép</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Mã NV</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Nhân viên</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Vai trò</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Phòng ban</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Trạng thái</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Ngày vào</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($employees as $employee)
                        <tr class="hover:bg-blue-50/30 transition-colors group" wire:key="emp-{{ $employee->id }}">
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $employee->code }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                        {{ strtoupper(mb_substr($employee->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $employee->name }}</p>
                                        <p class="text-xs text-gray-400"><i class="fa-solid fa-phone text-[10px] mr-1"></i>{{ $employee->phone }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $roleColors = [
                                        'admin' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/10',
                                        'director' => 'bg-red-50 text-red-700 ring-red-600/10',
                                        'manager' => 'bg-blue-50 text-blue-700 ring-blue-600/10',
                                        'supervisor' => 'bg-purple-50 text-purple-700 ring-purple-600/10',
                                        'team_leader' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                        'employee' => 'bg-gray-50 text-gray-700 ring-gray-600/10',
                                    ];
                                    $roleLabels = [
                                        'admin' => 'IT',
                                        'director' => 'Giám đốc',
                                        'manager' => 'Quản lý',
                                        'supervisor' => 'Quản đốc',
                                        'team_leader' => 'Tổ trưởng',
                                        'employee' => 'Nhân viên',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $roleColors[$employee->role] ?? '' }}">
                                    {{ $roleLabels[$employee->role] ?? $employee->role }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-700">
                                {{ $employee->department->name ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                        'inactive' => 'bg-red-50 text-red-700 ring-red-600/10',
                                        'on_leave' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                    ];
                                    $statusLabels = [
                                        'active' => 'Đang làm',
                                        'inactive' => 'Nghỉ việc',
                                        'on_leave' => 'Nghỉ phép',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $statusColors[$employee->status] ?? '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $employee->status === 'active' ? 'bg-emerald-500' : ($employee->status === 'inactive' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                                    {{ $statusLabels[$employee->status] ?? $employee->status }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-600">
                                {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a wire:navigate href="{{ route('employees.show', $employee) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-primary hover:bg-blue-50 transition-colors" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a wire:navigate href="{{ route('employees.edit', $employee) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                    <button wire:click="confirmDelete({{ $employee->id }})"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Xóa">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-users-slash text-4xl text-gray-300"></i>
                                    <p class="text-gray-400 font-medium">Không tìm thấy nhân viên nào</p>
                                    <p class="text-gray-300 text-xs">Thử thay đổi bộ lọc hoặc thêm nhân viên mới</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($employees->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $employees->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @if($confirmingDeleteId)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
             x-data x-init="document.body.classList.add('overflow-hidden')"
             x-on:remove.window="document.body.classList.remove('overflow-hidden')">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-0 overflow-hidden animate-scale-in"
                 @click.outside="$wire.cancelDelete()">
                <div class="px-6 pt-6 pb-4 text-center">
                    <div class="mx-auto w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-triangle-exclamation text-red-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Xác nhận xóa nhân viên</h3>
                    <p class="text-sm text-gray-500 mt-2">Nhân viên sẽ bị ẩn khỏi hệ thống nhưng có thể khôi phục.<br>Bạn có chắc chắn muốn tiếp tục?</p>
                </div>
                <div class="px-6 pb-6 flex items-center gap-3">
                    <button wire:click="cancelDelete"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                        Hủy bỏ
                    </button>
                    <button wire:click="deleteEmployee"
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors text-sm shadow-sm">
                        <i class="fa-solid fa-trash-can mr-1"></i>
                        Xóa nhân viên
                    </button>
                </div>
            </div>
        </div>
    @endif

    <style>
        @keyframes scale-in {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
        .animate-scale-in {
            animation: scale-in 0.2s ease-out;
        }
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
    </style>
</div>
