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

                    <div class="space-y-3">
                        @foreach($cart as $index => $item)
                        <div class="p-4 border border-gray-100 rounded-xl bg-gray-50/50 hover:bg-gray-50 transition-colors relative group" wire:key="cart-{{ $index }}">
                            <div class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
                                {{-- Sản phẩm --}}
                                <div class="md:col-span-2">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Sản phẩm <span class="text-red-500">*</span></label>
                                    <select wire:model.live="cart.{{ $index }}.product_id" class="w-full rounded-xl border-gray-100 text-xs font-bold shadow-sm focus:ring-1 focus:ring-primary focus:border-primary">
                                        <option value="">-- Chọn sản phẩm --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                [{{ $product->code }}] {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cart.'.$index.'.product_id') <span class="text-red-500 text-[10px] italic">{{ $message }}</span> @enderror
                                </div>
                                
                                {{-- Đơn vị --}}
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1 text-center">ĐVT</label>
                                    <div class="w-full px-3 py-2 bg-white border border-gray-100 rounded-xl text-center text-[10px] font-black text-gray-500 uppercase">
                                        {{ $cart[$index]['unit'] ?? '---' }}
                                    </div>
                                </div>

                                {{-- Số lượng --}}
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1 text-center">Số lượng</label>
                                    <input type="text" wire:model.live.debounce.500ms="cart.{{ $index }}.quantity" class="w-full rounded-xl border-gray-100 text-center text-xs font-black text-blue-700 bg-blue-50/50 focus:ring-1 focus:ring-blue-500" placeholder="0">
                                    @error('cart.'.$index.'.quantity') <span class="text-red-500 text-[10px] italic">{{ $message }}</span> @enderror
                                </div>

                                {{-- Đơn giá --}}
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1 text-right">Đơn giá</label>
                                    <input type="text" wire:model.live.debounce.500ms="cart.{{ $index }}.price" class="w-full rounded-xl border-gray-100 text-right text-xs font-bold text-gray-700 focus:ring-1 focus:ring-primary" placeholder="0">
                                </div>

                                {{-- Thành tiền --}}
                                <div class="md:col-span-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1 text-right">Thành tiền</label>
                                    <div class="w-full px-3 py-2 bg-amber-50 rounded-xl text-right text-xs font-black text-amber-700 border border-amber-100">
                                        @nfmt($cart[$index]['amount'] ?? 0)
                                    </div>
                                </div>
                            </div>

                            {{-- Nút xóa --}}
                            @if(count($cart) > 1)
                            <button type="button" wire:click="removeCartItem({{ $index }})" class="absolute -top-2 -right-2 w-6 h-6 bg-white border border-gray-100 text-gray-300 hover:text-red-500 hover:border-red-100 rounded-full shadow-sm transition-all flex items-center justify-center opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100">
                                <i class="fa-solid fa-xmark text-[10px]"></i>
                            </button>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right: Info -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 space-y-5 sticky top-6">
                    <h3 class="text-xs font-black uppercase tracking-widest text-blue-600 border-b border-gray-100 pb-3">
                        <i class="fa-solid fa-user-check mr-2"></i> Khách hàng & Thanh toán
                    </h3>
                    
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5 ml-1">Chọn Khách hàng <span class="text-red-500">*</span></label>
                        <select wire:model.live="customer_id" class="w-full rounded-xl border-gray-100 text-xs font-bold shadow-sm focus:ring-1 focus:ring-primary focus:border-primary mb-3">
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->customer_code }} - {{ $customer->name }}</option>
                            @endforeach
                        </select>

                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1 opacity-50">Tên khách xác nhận</label>
                        <input type="text" wire:model="customer_name" class="w-full rounded-xl border-gray-100 text-xs font-bold text-gray-800 bg-gray-50/50" placeholder="...">
                        @error('customer_name') <span class="text-red-500 text-[10px] italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Số điện thoại</label>
                            <input type="text" wire:model="customer_phone" class="w-full rounded-xl border-gray-100 text-xs font-bold" placeholder="...">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Ngày đặt</label>
                            <div class="w-full px-3 py-2 bg-gray-50 border border-transparent rounded-xl text-xs font-bold text-gray-500">
                                {{ now()->format('d/m/Y') }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Ghi chú giao hàng</label>
                        <textarea wire:model="note" rows="2" class="w-full rounded-xl border-gray-100 text-xs font-medium" placeholder="Giao giờ hành chính..."></textarea>
                    </div>

                    {{-- TỔNG CỘNG --}}
                    <div class="pt-5 border-t border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-[10px] font-black uppercase tracking-widest text-blue-600">Tổng cộng VNĐ</span>
                            <span class="text-2xl font-black text-blue-700 tracking-tighter">@nfmt($total_amount)</span>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-black uppercase tracking-widest text-[11px] hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center justify-center">
                            <i class="fa-solid fa-cart-arrow-down mr-2"></i> XÁC NHẬN CHỐT ĐƠN
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
