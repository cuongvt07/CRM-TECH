<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Chỉnh sửa phòng ban</h1>
            <nav class="text-sm text-gray-500 mt-1">
                <a wire:navigate href="{{ route('departments.index') }}" class="hover:text-primary transition-colors">Phòng ban</a>
                <span class="mx-1">/</span>
                <span class="text-gray-700">{{ $department->name }}</span>
                <span class="mx-1">/</span>
                <span class="text-gray-700">Chỉnh sửa</span>
            </nav>
        </div>
        <a wire:navigate href="{{ route('departments.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
            <i class="fa-solid fa-arrow-left"></i>
            Quay lại
        </a>
    </div>

    {{-- Form --}}
    <form wire:submit="save" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-user-pen text-amber-500"></i>
                Thông tin phòng ban
            </h2>
        </div>

        <div class="p-6 space-y-6">
            {{-- Row 1: Code & Name --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1.5 font-bold italic">Mã phòng ban (Cố định)</label>
                    <div class="w-full px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-500 font-mono">
                        {{ $code }}
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Tên phòng ban <span class="text-red-500">*</span>
                    </label>
                    <input wire:model="name" type="text"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors @error('name') border-red-400 @enderror" />
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Row 2: Head & Phone --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Trưởng phòng</label>
                    <select wire:model="head_id"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-colors">
                        <option value="">-- Chọn trưởng phòng --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->code }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Số điện thoại liên hệ</label>
                    <input wire:model="phone" type="text"
                           class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors" />
                </div>
            </div>

            {{-- Row 3: Description & Duties --}}
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Mô tả ngắn</label>
                    <textarea wire:model="description" rows="2"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nhiệm vụ, Chức năng & Nội quy chi tiết</label>
                    <textarea wire:model="duties" rows="8"
                              class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-colors"></textarea>
                    <p class="mt-1 text-[10px] text-gray-400 italic">* Nội dung này sẽ được hiển thị cho tất cả nhân viên thuộc phòng ban này xem.</p>
                </div>
            </div>

            {{-- Row 4: Status --}}
            <div class="max-w-xs">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Trạng thái hoạt động</label>
                <select wire:model="status"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary bg-white transition-colors">
                    <option value="active">Đang hoạt động</option>
                    <option value="inactive">Tạm ngưng</option>
                </select>
            </div>
        </div>

        {{-- Actions --}}
        <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-end gap-3">
            <a wire:navigate href="{{ route('departments.index') }}"
               class="px-5 py-2.5 border border-gray-200 text-gray-600 rounded-lg font-medium hover:bg-gray-50 transition-colors text-sm">
                Hủy
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white rounded-lg font-semibold shadow hover:bg-blue-700 transition-colors text-sm">
                <i class="fa-solid fa-floppy-disk"></i>
                Lưu thay đổi
            </button>
        </div>
    </form>
</div>
