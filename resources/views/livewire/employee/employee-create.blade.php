<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Thêm nhân viên mới</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a wire:navigate href="{{ route('employees.index') }}" class="hover:text-primary transition-colors">Nhân viên</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">Thêm mới</span>
            </nav>
        </div>
        <a wire:navigate href="{{ route('employees.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left"></i>
            Quay lại
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-user-plus text-primary"></i>
                Thông tin nhân viên
            </h2>
        </div>

        <div class="p-6 space-y-6">
            {{-- Row 1: Name & Auto-code Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5 font-bold italic text-primary">Mã nhân viên (Tự động)</label>
                    <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-400 italic">
                        Hệ thống tự động sinh (VD: EMP001)
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Họ tên <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text" placeholder="Nguyễn Văn A"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('name') border-red-400 @enderror" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 2: Phone & Email --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Số điện thoại <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="phone" type="text" placeholder="0901234567"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('phone') border-red-400 @enderror" />
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input wire:model="email" type="email" placeholder="email@domain.com"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('email') border-red-400 @enderror" />
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 3: Password --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="password" type="password" placeholder="Tối thiểu 6 ký tự"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('password') border-red-400 @enderror" />
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Xác nhận mật khẩu <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="password_confirmation" type="password" placeholder="Nhập lại mật khẩu"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" />
                </div>
            </div>

            <hr class="border-gray-100">

            {{-- Row 4: Role & Department --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Vai trò <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="role"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-colors">
                        <option value="employee">Nhân viên</option>
                        <option value="team_leader">Tổ trưởng</option>
                        <option value="manager">Quản lý</option>
                        <option value="supervisor">Quản đốc</option>
                        <option value="director">Giám đốc</option>
                        <option value="admin">IT</option>
                    </select>
                </div>
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-1.5">Phòng ban</label>
                    <select wire:model="department_id" id="department_id" 
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-colors @error('department_id') border-red-400 @enderror">
                        <option value="">-- Chọn phòng ban --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 5: Status & Hire Date --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Trạng thái <span class="text-red-500">*</span>
                    </label>
                    <select wire:model="status"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-colors">
                        <option value="active">Đang làm việc</option>
                        <option value="inactive">Nghỉ việc</option>
                        <option value="on_leave">Nghỉ phép</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Ngày vào làm</label>
                    <input wire:model="hire_date" type="date"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" />
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-3">
            <a wire:navigate href="{{ route('employees.index') }}"
               class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                Hủy
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition-colors text-sm">
                <i class="fa-solid fa-floppy-disk"></i>
                Lưu nhân viên
            </button>
        </div>
    </form>
</div>
