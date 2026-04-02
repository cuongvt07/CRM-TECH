<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Chi tiết nhân viên</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a wire:navigate href="{{ route('employees.index') }}" class="hover:text-primary transition-colors">Nhân viên</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">{{ $user->name }}</span>
            </nav>
        </div>
        <div class="flex items-center gap-2">
            <a wire:navigate href="{{ route('employees.edit', $user) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg font-medium hover:bg-amber-600 transition-colors text-sm shadow-sm">
                <i class="fa-solid fa-pen-to-square"></i>
                Chỉnh sửa
            </a>
            <a wire:navigate href="{{ route('employees.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại
            </a>
        </div>
    </div>

    {{-- Profile Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        {{-- Banner --}}
        <div class="h-32 bg-gradient-to-r from-primary via-blue-500 to-indigo-600 relative">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDE0VjZINHYyOGgyNFYxNGg4ek00IDI4VjZoMjR2OEgxNHYxNEg0eiIvPjwvZz48L2c+PC9zdmc+')] opacity-30"></div>
        </div>

        {{-- Avatar & Basic Info --}}
        <div class="px-6 pb-6 -mt-12 relative">
            <div class="flex flex-col md:flex-row md:items-end gap-5">
                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary to-blue-400 flex items-center justify-center text-white text-3xl font-bold shadow-lg border-4 border-white">
                    {{ strtoupper(mb_substr($user->name, 0, 1)) }}
                </div>
                <div class="flex-1 pt-2">
                    <h2 class="text-xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500 flex items-center gap-2 mt-0.5">
                        <span class="font-mono bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-xs">{{ $user->code }}</span>
                        <span>•</span>
                        <span><i class="fa-solid fa-phone text-[11px] mr-1"></i>{{ $user->phone }}</span>
                        <span>•</span>
                        <span class="text-primary font-medium">{{ $user->department->name ?? 'Chưa cập nhật' }}</span>
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $statusColors = [
                            'active' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                            'inactive' => 'bg-red-50 text-red-700 ring-red-600/10',
                            'on_leave' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                        ];
                        $statusLabels = [
                            'active' => 'Đang làm việc',
                            'inactive' => 'Nghỉ việc',
                            'on_leave' => 'Nghỉ phép',
                        ];
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
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold ring-1 ring-inset {{ $roleColors[$user->role] ?? '' }}">
                        <i class="fa-solid fa-shield-halved text-[10px]"></i>
                        {{ $roleLabels[$user->role] ?? $user->role }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold ring-1 ring-inset {{ $statusColors[$user->status] ?? '' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : ($user->status === 'inactive' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                        {{ $statusLabels[$user->status] ?? $user->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Detail Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Personal Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-id-card text-primary text-sm"></i>
                    Thông tin cá nhân
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Mã nhân viên</span>
                    <span class="text-sm font-medium text-gray-900 font-mono">{{ $user->code }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Họ tên</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->name }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Số điện thoại</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->phone }}</span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-500">Email</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->email ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Work Information --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-amber-500 text-sm"></i>
                    Thông tin công việc
                </h3>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Phòng ban</span>
                    <span class="text-sm font-medium text-gray-900">{{ $user->department->name ?? '—' }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Vai trò</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $roleColors[$user->role] ?? '' }}">
                        {{ $roleLabels[$user->role] ?? $user->role }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Trạng thái</span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium ring-1 ring-inset {{ $statusColors[$user->status] ?? '' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : ($user->status === 'inactive' ? 'bg-red-500' : 'bg-amber-500') }}"></span>
                        {{ $statusLabels[$user->status] ?? $user->status }}
                    </span>
                </div>
                <div class="flex items-center justify-between py-2">
                    <span class="text-sm text-gray-500">Ngày vào làm</span>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $user->hire_date ? \Carbon\Carbon::parse($user->hire_date)->format('d/m/Y') : '—' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
