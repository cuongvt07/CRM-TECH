<div>
    <div class="flex items-center mb-6">
        <a wire:navigate href="{{ route('categories.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Sửa danh mục: {{ $category->name }}</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
        <form wire:submit="save" class="p-6 space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên danh mục <span class="text-red-500">*</span></label>
                <input type="text" id="name" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-500 @enderror" placeholder="Nhập tên danh mục">
                @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
                <textarea id="description" wire:model="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('description') border-red-500 @enderror" placeholder="Nhập mô tả danh mục"></textarea>
                @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <a wire:navigate href="{{ route('categories.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3 transition-colors">
                    Hủy
                </a>
                <button type="submit" class="px-4 py-2 bg-primary border text-white rounded-md hover:bg-blue-600 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="fa-solid fa-save mr-2"></i> Cập nhật
                </button>
            </div>
        </form>
    </div>
</div>
