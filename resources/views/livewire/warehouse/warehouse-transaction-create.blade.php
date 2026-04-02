<div>
    <div class="flex items-center mb-6">
        <a wire:navigate href="{{ route('warehouse.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">
            @if($type === 'import')
                @if($warehouse_code === 'FINISHED_GOODS')
                    <i class="fa-solid fa-industry text-blue-600 mr-2"></i> Phiếu Nhập Kho Nội Bộ (Thành phẩm)
                @else
                    <i class="fa-solid fa-arrow-down text-blue-500 mr-2"></i> Phiếu Nhập Kho
                @endif
            @else
                <i class="fa-solid fa-arrow-up text-orange-500 mr-2"></i> Phiếu Xuất Kho
            @endif
            @if($warehouse_code !== 'FINISHED_GOODS' || $type !== 'import')
                - {{ $warehouse?->name ?? 'Chưa xác định' }}
            @endif
        </h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden max-w-3xl">
        <form wire:submit="save" class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($warehouse_code === 'FINISHED_GOODS') Danh mục thành phẩm @elseif($warehouse_code === 'RAW_MAT') Danh mục nguyên liệu @else Danh mục vật tư @endif
                    </label>
                    <select wire:model.live="category_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">-- Tất cả danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="relative">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($warehouse_code === 'FINISHED_GOODS') Tìm & Chọn Thành phẩm @elseif($warehouse_code === 'RAW_MAT') Tìm & Chọn Nguyên liệu @else Tìm & Chọn Vật tư @endif
                        <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" 
                            wire:model.live.debounce.300ms="productSearch" 
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 pr-10" 
                            placeholder="{{ $warehouse_code === 'FINISHED_GOODS' ? 'Gõ tên hoặc mã để tìm...' : 'Gõ tên để tìm...' }}"
                            autocomplete="off"
                            autofocus>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-search"></i>
                        </div>
                    </div>

                    @if($productSearch && !$product_id)
                        <div class="absolute z-50 mt-1 w-full bg-white rounded-md shadow-xl border border-gray-200 max-h-60 overflow-y-auto">
                            @forelse($products as $prod)
                                <button type="button" 
                                    wire:click="selectProduct({{ $prod->id }})" 
                                    class="w-full text-left px-4 py-3 hover:bg-blue-50 transition-colors border-b border-gray-50 flex justify-between items-center group">
                                    <div class="flex-1">
                                        @if($warehouse_code === 'FINISHED_GOODS')
                                            <p class="font-bold text-gray-900 group-hover:text-blue-700">[{{ $prod->code }}] {{ $prod->name }}</p>
                                        @else
                                            <p class="font-bold text-gray-900 group-hover:text-blue-700">{{ $prod->name }}</p>
                                        @endif
                                        <p class="text-[10px] text-gray-500 uppercase">{{ $prod->category?->name }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-xs font-bold text-green-600 block">Tồn: {{ number_format($prod->inventory?->quantity ?? 0) }}</span>
                                        <span class="text-[10px] text-gray-400">{{ $prod->unit }}</span>
                                    </div>
                                </button>
                            @empty
                                <div class="px-4 py-6 text-center text-gray-400 text-sm italic">
                                    Không tìm thấy sản phẩm phù hợp...
                                </div>
                            @endforelse
                        </div>
                    @elseif($product_id)
                        <div class="mt-2 flex items-center justify-between bg-blue-50 p-2 rounded-lg border border-blue-100">
                            <div class="flex items-center">
                                <i class="fa-solid fa-check-circle text-blue-600 mr-2"></i>
                                <span class="text-sm font-bold text-blue-800">{{ $productSearch }}</span>
                            </div>
                            <button type="button" wire:click="selectProduct(null)" class="text-xs text-red-500 hover:underline font-bold uppercase tracking-wider">Thay đổi</button>
                        </div>
                    @endif
                    @error('product_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ngày thực hiện <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="transaction_date" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                    @error('transaction_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số lượng <span class="text-red-500">*</span></label>
                    <input type="number" wire:model="quantity" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Nhập số lượng">
                    @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($warehouse_code === 'FINISHED_GOODS' && $type === 'import')
                            Đơn giá bán (VNĐ)
                        @else
                            Đơn giá vốn (VNĐ)
                        @endif
                    </label>
                    <input type="number" wire:model="unit_price" min="0" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="0">
                    @error('unit_price') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số hóa đơn / Chứng từ</label>
                    <input type="text" wire:model="invoice_number" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Ví dụ: HD-20260402">
                    @error('invoice_number') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            @if($warehouse_code !== 'FINISHED_GOODS')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2 border-t border-gray-50">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        @if($type === 'import') Nhà cung cấp @else Khách hàng @endif
                    </label>
                    <input type="text" wire:model="partner_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Tên đơn vị/cá nhân">
                    @error('partner_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                    <input type="text" wire:model="partner_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="09xxxxxxxx">
                    @error('partner_phone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú</label>
                <textarea wire:model="note" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Ghi chú thêm (nếu có)"></textarea>
                @error('note') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <a wire:navigate href="{{ route('warehouse.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 mr-3 transition-colors">
                    Hủy
                </a>
                <button type="submit" class="px-6 py-2.5 {{ $type === 'import' ? 'bg-blue-600 hover:bg-blue-700' : 'bg-orange-500 hover:bg-orange-600' }} text-white rounded-md transition-colors focus:ring-2 focus:ring-offset-2 flex items-center font-medium">
                    @if($type === 'import')
                        @if($warehouse_code === 'FINISHED_GOODS')
                            <i class="fa-solid fa-industry mr-2"></i> Xác nhận Nhập Kho Nội Bộ
                        @else
                            <i class="fa-solid fa-arrow-down mr-2"></i> Xác nhận Nhập Kho
                        @endif
                    @else
                        <i class="fa-solid fa-arrow-up mr-2"></i> Xác nhận Xuất Kho
                    @endif
                </button>
            </div>
        </form>
    </div>
</div>
