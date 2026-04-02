<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Chi tiết phòng ban</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a wire:navigate href="{{ route('departments.index') }}" class="hover:text-primary transition-colors">Phòng ban</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">{{ $department->name }}</span>
            </nav>
        </div>
        <div class="flex items-center gap-2">
            <a wire:navigate href="{{ route('departments.edit', $department) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg font-medium hover:bg-amber-600 transition-colors text-sm shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i>
                Chỉnh sửa
            </a>
            <a wire:navigate href="{{ route('departments.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Department Info Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-sitemap text-primary"></i>
                Thông tin chung
            </h2>
            @if($department->status === 'active')
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    Đang hoạt động
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-600/10">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                    Tạm ngưng
                </span>
            @endif
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Mã phòng ban</p>
                    <p class="font-mono text-sm inline-block bg-gray-100 px-2 py-1 rounded text-gray-700">{{ $department->code }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Trưởng phòng</p>
                    <p class="text-sm font-semibold text-gray-900 italic text-primary">
                        {{ $department->head->name ?? 'Chưa cập nhật' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-1">Liên hệ</p>
                    <p class="text-sm text-gray-700">
                        @if($department->phone)
                            <i class="fa-solid fa-phone mr-1 text-gray-400"></i> {{ $department->phone }}
                        @else
                            —
                        @endif
                    </p>
                </div>
                <div class="md:col-span-3">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Mô tả ngắn</p>
                    <p class="text-sm text-gray-600 leading-relaxed mb-6">{{ $department->description ?? 'Không có mô tả' }}</p>

                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider mb-2">Nhiệm vụ, Chức năng & Nội quy chi tiết</p>
                    <div class="bg-gray-50 rounded-lg p-5 border border-gray-100 text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">
                        {{ $department->duties ?? 'Chưa cập nhật nội dung chi tiết.' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Employees List --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <i class="fa-solid fa-users text-gray-400"></i>
                Danh sách nhân sự ({{ $department->users()->count() }})
            </h3>
            <div class="relative w-64">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Tìm nhân viên..." 
                       class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-lg text-xs focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" />
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 border-b border-gray-100">
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Mã NV</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Nhân viên</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Vai trò</th>
                            <th class="text-left px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Trạng thái</th>
                            <th class="text-right px-5 py-3 font-semibold text-gray-600 text-xs uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($employees as $employee)
                            <tr class="hover:bg-blue-50/30 transition-colors group {{ $department->head_id === $employee->id ? 'bg-amber-50/50' : '' }}">
                                <td class="px-5 py-3">
                                    <span class="font-mono text-xs text-gray-600">{{ $employee->code }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white font-bold text-xs shadow-sm">
                                            {{ strtoupper(mb_substr($employee->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <p class="font-semibold text-gray-900">{{ $employee->name }}</p>
                                                @if($department->head_id === $employee->id)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">Trưởng phòng</span>
                                                @endif
                                            </div>
                                            <p class="text-[10px] text-gray-400"><i class="fa-solid fa-phone mr-1"></i>{{ $employee->phone }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @php
                                        $roleLabels = [
                                            'admin' => 'IT',
                                            'director' => 'Giám đốc',
                                            'manager' => 'Quản lý',
                                            'supervisor' => 'Quản đốc',
                                            'team_leader' => 'Tổ trưởng',
                                            'employee' => 'Nhân viên',
                                        ];
                                    @endphp
                                    <span class="text-xs text-gray-600">{{ $roleLabels[$employee->role] ?? $employee->role }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    @if($employee->status === 'active')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Đang làm
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-gray-50 text-gray-600 border border-gray-100">
                                            {{ $employee->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a wire:navigate href="{{ route('employees.show', $employee) }}"
                                       class="text-primary hover:text-blue-700 font-medium text-xs">
                                        Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center">
                                    <p class="text-gray-400 text-xs italic">Không có nhân sự nào thuộc phòng ban này</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
