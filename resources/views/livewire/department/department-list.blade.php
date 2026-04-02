<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Quản lý phòng ban</h1>
            <p class="text-sm text-gray-500 mt-1">Quản lý cơ cấu tổ chức và nhân sự theo phòng ban</p>
        </div>
        <a wire:navigate href="{{ route('departments.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition-colors text-sm">
            <i class="fa-solid fa-plus"></i>
            Thêm phòng ban
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
            <div class="md:col-span-3 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text"
                       placeholder="Tìm theo tên phòng ban, mã phòng..."
                       class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" />
            </div>
            <select wire:model.live="statusFilter"
                    class="w-full px-3 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="active">Đang hoạt động</option>
                <option value="inactive">Tạm ngưng</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50/80 border-b border-gray-100">
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Mã phòng</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Tên phòng ban</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Trưởng phòng</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Số nhân viên</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Trạng thái</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-600 text-xs uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($departments as $department)
                        <tr class="hover:bg-blue-50/30 transition-colors group" wire:key="dept-{{ $department->id }}">
                            <td class="px-5 py-4">
                                <span class="font-mono text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $department->code }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center text-primary shadow-sm group-hover:bg-primary group-hover:text-white transition-colors">
                                        <i class="fa-solid fa-sitemap"></i>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $department->name }}</p>
                                        @if($department->description)
                                            <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ $department->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-700 font-medium">
                                {{ $department->head->name ?? '—' }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                    {{ $department->users_count }} nhân sự
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($department->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Hoạt động
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-50 text-gray-600 ring-1 ring-inset ring-gray-600/10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Tạm ngưng
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a wire:navigate href="{{ route('departments.show', $department) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-primary hover:bg-blue-50 transition-colors" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye text-sm"></i>
                                    </a>
                                    <a wire:navigate href="{{ route('departments.edit', $department) }}"
                                       class="p-2 rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square text-sm"></i>
                                    </a>
                                    <button wire:click="confirmDelete({{ $department->id }})"
                                            class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors" title="Xóa">
                                        <i class="fa-solid fa-trash-can text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-16 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fa-solid fa-sitemap text-4xl text-gray-300"></i>
                                    <p class="text-gray-400 font-medium">Không tìm thấy phòng ban nào</p>
                                    <p class="text-gray-300 text-xs">Thử thay đổi bộ lọc hoặc thêm phòng ban mới</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($departments->hasPages())
            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $departments->links() }}
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
                    <h3 class="text-lg font-bold text-gray-900">Xác nhận xóa phòng ban</h3>
                    <p class="text-sm text-gray-500 mt-2">Dữ liệu sẽ được ẩn khỏi hệ thống nhưng có thể khôi phục.<br>Các nhân viên thuộc phòng ban này sẽ mất liên kết phòng ban.</p>
                </div>
                <div class="px-6 pb-6 flex items-center gap-3">
                    <button wire:click="cancelDelete"
                            class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                        Hủy bỏ
                    </button>
                    <button wire:click="deleteDepartment"
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors text-sm shadow-sm">
                        <i class="fa-solid fa-trash-can mr-1"></i>
                        Xóa phòng ban
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
