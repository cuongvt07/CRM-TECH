<div>
    <div class="flex items-center mb-6">
        <a wire:navigate href="{{ route('products.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Thêm sản phẩm mới</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-5xl">
        <form wire:submit="save" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Left Column - Image & Basic -->
                <div class="md:col-span-1 space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hình ảnh sản phẩm</label>
                        <div class="flex flex-col items-center justify-center w-full">
                            <label for="image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 overflow-hidden">
                                @if ($image)
                                    <img src="{{ $image->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-400 mb-3"></i>
                                        <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                        <p class="text-xs text-gray-500">PNG, JPG or WEBP (Max 2MB)</p>
                                    </div>
                                @endif
                                <input id="image" type="file" wire:model="image" class="hidden" accept="image/*" />
                            </label>
                            <div wire:loading wire:target="image" class="mt-2 text-primary text-sm font-medium">Đang tải ảnh lên...</div>
                        </div>
                        @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Right Column - Details -->
                <div class="md:col-span-2 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Mã sản phẩm (SKU) <span class="text-red-500">*</span></label>
                                <input type="text" id="code" wire:model="code" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('code') border-red-500 @enderror" placeholder="Ví dụ: SP001">
                                @error('code') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Hãng sản xuất</label>
                                <input type="text" id="brand" wire:model="brand" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('brand') border-red-500 @enderror" placeholder="Ví dụ: Samsung, Apple...">
                                @error('brand') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Danh mục</label>
                            <select id="category_id" wire:model="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('category_id') border-red-500 @enderror">
                                <option value="">Chọn danh mục</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-1">Kho lưu trữ <span class="text-red-500">*</span></label>
                                <select id="warehouse_id" wire:model="warehouse_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('warehouse_id') border-red-500 @enderror">
                                    <option value="">-- Chọn Kho --</option>
                                    @if(isset($warehouses))
                                        @foreach($warehouses as $wh)
                                            <option value="{{ $wh->id }}">{{ $wh->name }} ({{ $wh->code }})</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('warehouse_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">Vị trí cụ thể (Kệ/Dãy...)</label>
                                <input type="text" id="location" wire:model="location" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('location') border-red-500 @enderror" placeholder="Ví dụ: Kệ A1, Tầng 2">
                                @error('location') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" id="name" wire:model="name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('name') border-red-500 @enderror" placeholder="Nhập tên sản phẩm">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Giá bán <span class="text-red-500">*</span></label>
                            <input type="number" id="price" wire:model="price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('price') border-red-500 @enderror" placeholder="0">
                            @error('price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-1">Đơn vị tính <span class="text-red-500">*</span></label>
                            <input type="text" id="unit" wire:model="unit" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('unit') border-red-500 @enderror" placeholder="Cái, Hộp, kg...">
                            @error('unit') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="min_stock" class="block text-sm font-medium text-gray-700 mb-1">Tồn kho tối thiểu <span class="text-red-500">*</span></label>
                            <input type="number" id="min_stock" wire:model="min_stock" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('min_stock') border-red-500 @enderror" placeholder="0">
                            @error('min_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="max_stock" class="block text-sm font-medium text-gray-700 mb-1">Tồn kho tối đa</label>
                            <input type="number" id="max_stock" wire:model="max_stock" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('max_stock') border-red-500 @enderror" placeholder="Bỏ trống nếu không dùng">
                            @error('max_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Mô tả chi tiết</label>
                        <textarea id="description" wire:model="description" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('description') border-red-500 @enderror" placeholder="Nhập mô tả sản phẩm"></textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái <span class="text-red-500">*</span></label>
                        <select id="status" wire:model="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error('status') border-red-500 @enderror">
                            <option value="active">Active (Đang bán)</option>
                            <option value="inactive">Inactive (Ngừng bán)</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100 mt-8">
                <a wire:navigate href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3 transition-colors">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2 bg-primary border text-white rounded-md hover:bg-blue-600 transition-colors focus:ring-2 focus:ring-offset-2 focus:ring-primary flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
</div>
