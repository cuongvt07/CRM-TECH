<div>
    <div class="flex items-center mb-6">
        <a wire:navigate href="{{ route('orders.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <h2 class="text-2xl font-semibold text-gray-800">Tạo mới Đơn Hàng (Bán hàng)</h2>
    </div>

    <div class="space-y-6">
        <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Left: Items -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Chi tiết sản phẩm</h3>
                        <button type="button" wire:click="addCartItem" class="text-sm px-3 py-1 bg-green-50 text-green-600 font-medium rounded hover:bg-green-100 transition-colors">
                            <i class="fa-solid fa-plus mr-1"></i> Thêm dòng
                        </button>
                    </div>

                    @error('cart') <span class="text-red-500 text-sm block mb-4">{{ $message }}</span> @enderror

                    <div class="space-y-4">
                        @foreach($cart as $index => $item)
                        <div class="flex items-end gap-4 p-4 border border-gray-100 rounded-lg bg-gray-50" wire:key="cart-{{ $index }}">
                            <div class="flex-1">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Sản phẩm <span class="text-red-500">*</span></label>
                                <select wire:model="cart.{{ $index }}.product_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                                    <option value="">-- Chọn sản phẩm --</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->code }} - {{ $product->name }} 
                                            (Tồn: {{ $product->inventory?->quantity ?? 0 }} | Giá: {{ number_format($product->price, 0, ',', '.') }}đ)
                                        </option>
                                    @endforeach
                                </select>
                                @error('cart.'.$index.'.product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="w-24">
                                <label class="block text-xs font-medium text-gray-500 mb-1">SL <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="cart.{{ $index }}.quantity" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" min="1">
                                @error('cart.'.$index.'.quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            @if(count($cart) > 1)
                            <button type="button" wire:click="removeCartItem({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-md transition-colors" title="Xóa dòng này">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Info -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 space-y-4 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-800 border-b pb-3">Thông tin khách hàng</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Chọn Khách hàng <span class="text-red-500">*</span></label>
                        <select wire:model.live="customer_id" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 mb-3">
                            <option value="">-- Chọn từ danh sách khách hàng --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_code }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>

                        <label class="block text-xs font-semibold text-gray-400 uppercase mb-1">Tên khách hàng xác nhận</label>
                        <input type="text" wire:model="customer_name" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 bg-gray-50" placeholder="Chọn khách hàng phía trên hoặc tự nhập...">
                        @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                        <input type="text" wire:model="customer_phone" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="09xxxxxxxxx">
                        @error('customer_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú giao hàng</label>
                        <textarea wire:model="note" rows="3" class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" placeholder="Giao giờ hành chính..."></textarea>
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit" class="w-full py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-600 transition-colors shadow-sm flex items-center justify-center">
                            <i class="fa-solid fa-check mr-2"></i> Xác nhận Đặt Hàng
                        </button>
                    </div>
                    
                    <div class="text-xs text-gray-500 bg-blue-50 p-3 rounded-md mt-4 border border-blue-100">
                        <i class="fa-solid fa-circle-info text-blue-500 mr-1"></i>
                        Hệ thống sẽ tự động đối chiếu Tồn kho thiết lập thông báo cho Admin/Kho tùy tình huống.
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
