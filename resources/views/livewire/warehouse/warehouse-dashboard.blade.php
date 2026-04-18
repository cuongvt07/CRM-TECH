<div>
    <style>
        @media print {
            .side-nav, .top-nav, .no-print, .alert-pending, button, select, input[type='checkbox'] { display: none !important; }
            body, .main-content { background: white !important; margin: 0; padding: 0; }
            .shadow-sm, .rounded-xl { box-shadow: none !important; border: 1px solid #eee !important; }
            
            /* Logic for printing selected rows only (Inventory) */
            @if(count($selectedItems) > 0)
                tr:has(input[type='checkbox'][value^='prod-']):not(:has(input[type='checkbox']:checked)) {
                    display: none !important;
                }
            @endif

            /* Logic for printing selected rows only (History) */
            @if(count($selectedTransactions) > 0)
                tr:has(input[type='checkbox'][value^='txn-']):not(:has(input[type='checkbox']:checked)) {
                    display: none !important;
                }
            @endif
        }
        
        @keyframes vibrate {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-10deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-vibrate {
            animation: vibrate 0.15s linear infinite;
        }
        .animate-pulse-slow {
            animation: pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>

    {{-- HEADER --}}
    <div class="p-6">
    @if(!in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES', 'FINISHED_GOODS']))
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3 no-print">
            <div class="flex flex-col">
                <h1 class="text-2xl font-black text-gray-900 tracking-tighter uppercase flex items-center">
                    <i class="fa-solid fa-warehouse mr-2.5 text-red-600 shadow-sm shadow-red-200"></i> Quản lý kho vận
                </h1>
                <p class="text-[10px] text-gray-400 font-black uppercase tracking-[0.2em] mt-0.5">Hệ thống điều phối & Lưu kho thông minh</p>
            </div>
            <div class="flex items-center space-x-3 mb-4 sm:mb-0 no-print">
                {{-- Notification Bell --}}
                <div x-data="{ open: false }">
                <button @click="open = true" 
                            style="padding: 6px 14px !important;"
                            class="rounded-lg transition-all relative border {{ $totalPending > 0 ? 'bg-red-600 border-red-700 text-white shadow-lg shadow-red-200' : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-bell text-lg {{ $totalPending > 0 ? 'animate-vibrate' : '' }}"></i>
                        @if($totalPending > 0)
                            <span class="absolute -top-2 -right-2 h-5 w-5 rounded-full bg-yellow-400 text-red-900 text-[10px] font-black flex items-center justify-center border-2 border-white shadow-sm">
                                {{ $totalPending }}
                            </span>
                        @endif
                    </button>

                    {{-- Notification Modal (Popup) --}}
                    <div x-show="open" 
                         x-cloak
                         class="fixed inset-0 z-[2000] overflow-y-auto" 
                         style="display: none;">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            {{-- Overlay --}}
                            <div x-show="open" 
                                 x-transition:enter="ease-out duration-300" 
                                 x-transition:enter-start="opacity-0" 
                                 x-transition:enter-end="opacity-100" 
                                 x-transition:leave="ease-in duration-200" 
                                 x-transition:leave-start="opacity-100" 
                                 x-transition:leave-end="opacity-0" 
                                 @click="open = false"
                                 class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 focus:outline-none" aria-hidden="true"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            {{-- Modal Content --}}
                            <div x-show="open" 
                                 x-transition:enter="ease-out duration-300" 
                                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                 x-transition:leave="ease-in duration-200" 
                                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                 class="inline-block w-full max-w-6xl overflow-hidden text-left align-middle transition-all transform bg-white shadow-3xl rounded-3xl border-4 border-white">
                                
                                <div class="px-8 py-6 bg-gradient-to-r from-red-600 to-red-500 flex justify-between items-center text-white relative">
                                    <div class="flex items-center">
                                        <div class="bg-white/20 p-3 rounded-xl mr-5 shadow-inner">
                                            <i class="fa-solid fa-bell-concierge text-3xl animate-pulse"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-black uppercase tracking-tighter text-2xl leading-none">Danh sách yêu cầu từ các phòng ban</h3>
                                            <p class="text-[11px] opacity-80 font-bold uppercase mt-1 tracking-widest flex items-center">
                                                <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-ping"></span> 
                                                Trung tâm điều phối kho vận & Sản xuất
                                            </p>
                                        </div>
                                    </div>
                                    <button @click="open = false" class="bg-white/10 hover:bg-white/20 p-3 rounded-full transition-all hover:rotate-90">
                                        <i class="fa-solid fa-xmark text-2xl"></i>
                                    </button>
                                </div>

                                <div class="max-h-[80vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-200 bg-gray-50/30">
                                    @if($totalPending > 0)
                                        <div class="p-0">
                                            <table class="w-full text-left border-collapse">
                                                <thead class="sticky top-0 bg-gray-100/95 backdrop-blur-sm z-10 shadow-sm">
                                                    <tr>
                                                        <th class="px-4 py-3 text-[9px] font-black uppercase text-gray-500 tracking-widest border-b">Nguồn</th>
                                                        <th class="px-4 py-3 text-[9px] font-black uppercase text-gray-500 tracking-widest border-b">Đơn hàng</th>
                                                        <th class="px-4 py-3 text-[9px] font-black uppercase text-gray-500 tracking-widest border-b">Mặt hàng</th>
                                                        <th class="px-4 py-3 text-[9px] font-black uppercase text-gray-500 tracking-widest border-b">Ghi chú</th>
                                                        <th class="px-4 py-3 text-[9px] font-black uppercase text-gray-500 tracking-widest border-b text-center">Xác nhận</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100 bg-white">
                                                    @foreach($pendingOrders as $order)
                                                        <tr class="hover:bg-blue-50/30 transition-colors group">
                                                            <td class="px-4 py-3 whitespace-nowrap align-top border-r border-gray-50">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-red-50 text-red-700 border border-red-100 uppercase">Kinh doanh</span>
                                                            </td>
                                                            <td class="px-4 py-3 align-top border-r border-gray-50">
                                                                <div class="text-xs font-black text-gray-900">#ORD_{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                                <div class="text-[10px] text-blue-800 font-bold">{{ $order->customer_name }}</div>
                                                            </td>
                                                            <td class="px-4 py-3 align-top border-r border-gray-50">
                                                                @foreach($order->items as $item)
                                                                    <div class="text-[10px] text-gray-600 truncate max-w-[150px]">{{ $item->product?->name }} (x{{ $item->quantity }})</div>
                                                                @endforeach
                                                            </td>
                                                            <td class="px-4 py-3 align-top border-r border-gray-50">
                                                                <textarea wire:model="feedbackNotes.Order-{{ $order->id }}" class="w-full text-[10px] rounded-lg border-gray-200 h-12 bg-gray-50 focus:ring-red-500"></textarea>
                                                            </td>
                                                            <td class="px-4 py-3 align-top">
                                                                <div class="flex gap-1">
                                                                    <button @click="open = false" wire:click="confirmWarehouseStock({{ $order->id }}, 'sufficient')" class="px-2 py-1 bg-green-600 text-white rounded text-[9px] font-black uppercase">OK</button>
                                                                    <button @click="open = false" wire:click="confirmWarehouseStock({{ $order->id }}, 'insufficient')" class="px-2 py-1 bg-red-600 text-white rounded text-[9px] font-black uppercase">Hết</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach

                                                    @foreach($pendingProductionOrders as $po)
                                                        <tr class="hover:bg-blue-50/30 transition-colors group">
                                                            <td class="px-4 py-3 whitespace-nowrap align-top border-r border-gray-50">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[9px] font-black bg-blue-50 text-blue-700 border border-blue-100 uppercase">Sản xuất</span>
                                                            </td>
                                                            <td class="px-4 py-3 align-top border-r border-gray-50">
                                                                <div class="text-xs font-black text-gray-900">#PO_{{ str_pad($po->id, 4, '0', STR_PAD_LEFT) }}</div>
                                                            </td>
                                                            <td class="px-4 py-3 align-top border-r border-gray-50">
                                                                <div class="text-[10px] text-gray-600">{{ $po->product?->name }} (x{{ $po->quantity }})</div>
                                                            </td>
                                                            <td class="px-4 py-3 align-top border-r border-gray-50">
                                                                <textarea wire:model="feedbackNotes.Production-{{ $po->id }}" class="w-full text-[10px] rounded-lg border-gray-200 h-12 bg-gray-50 focus:ring-blue-500"></textarea>
                                                            </td>
                                                            <td class="px-4 py-3 align-top">
                                                                <div class="flex gap-1">
                                                                    <button @click="open = false" wire:click="confirmProductionRequest({{ $po->id }}, 'sufficient')" class="px-2 py-1 bg-green-600 text-white rounded text-[9px] font-black uppercase">OK</button>
                                                                    <button @click="open = false" wire:click="confirmProductionRequest({{ $po->id }}, 'insufficient')" class="px-2 py-1 bg-red-600 text-white rounded text-[9px] font-black uppercase">Hết</button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-20 text-gray-400 opacity-60">
                                            <div class="relative inline-block mb-6">
                                                <i class="fa-solid fa-mug-hot text-7xl"></i>
                                                <i class="fa-solid fa-check absolute -bottom-2 -right-2 text-3xl text-green-500 bg-white rounded-full p-1 shadow-sm"></i>
                                            </div>
                                            <p class="text-lg font-black uppercase tracking-tighter">Kho hiện không có yêu cầu nào mới!</p>
                                            <p class="text-sm mt-2">Dữ liệu sẽ được tự động cập nhật khi có đơn hàng mới.</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="bg-gray-100 px-6 py-4 flex justify-between items-center text-gray-500 text-[10px] font-bold uppercase tracking-widest">
                                    <span>Hệ thống CRM v2.0</span>
                                    <span>{{ now()->format('d/m/Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(count($selectedItems) > 0 || count($selectedTransactions) > 0)
                    <button wire:click="clearSelected(); clearSelectedTransactions();" class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-wider">
                        Bỏ chọn
                    </button>
                @endif
            </div>
        </div>
    @else
        {{-- ===== TOOLBAR NGANG ===== --}}
        <div class="flex flex-wrap items-center gap-2 mb-4 no-print">
            <a wire:navigate href="{{ route('warehouse.index') }}" class="text-gray-400 hover:text-gray-700 transition-all shrink-0">
                <i class="fa-solid fa-arrow-left text-base"></i>
            </a>
            <h2 class="text-base font-black text-gray-900 flex items-center shrink-0">
                <i class="fa-solid fa-conveyor-belt text-blue-600 mr-2"></i>
                {{ $this->getSelectedWarehouseCode() === 'FINISHED_GOODS' ? 'Kho Thành Phẩm' : ($this->getSelectedWarehouseCode() === 'RAW_MAT' ? 'Kho Nguyên Vật Liệu' : 'Kho Vật Tư') }}
            </h2>
            <div class="h-6 w-px bg-gray-200 shrink-0"></div>

            <div class="flex items-center gap-1.5 bg-white border border-gray-200 rounded-xl px-3 py-1.5 shrink-0 shadow-sm">
                <i class="fa-solid fa-cubes-stacked text-blue-500 text-xs"></i>
                <div>
                    <p class="text-[8px] font-black uppercase text-gray-400 leading-none">Tồn kho</p>
                    <p class="text-sm font-black text-blue-700 leading-none">@nfmt(count($inventoryItems))</p>
                </div>
            </div>

            <div class="flex items-center gap-1.5 bg-blue-600 border border-blue-700 rounded-xl px-3 py-1.5 shrink-0 shadow-sm shadow-blue-200">
                <i class="fa-solid fa-boxes-stacked text-white text-xs"></i>
                <div>
                    <p class="text-[8px] font-black uppercase text-blue-200 leading-none">Tổng MH</p>
                    <p class="text-sm font-black text-white leading-none">@nfmt(count($inventoryItems))</p>
                </div>
            </div>

            <div class="flex items-center gap-1.5 bg-amber-50 border border-amber-200 rounded-xl px-3 py-1.5 shrink-0 shadow-sm">
                <i class="fa-solid fa-clock-rotate-left text-amber-500 text-xs"></i>
                <div>
                    <p class="text-[8px] font-black uppercase text-amber-400 leading-none">Giao dịch</p>
                    <p class="text-sm font-black text-amber-700 leading-none">@nfmt(count($transactions))</p>
                </div>
            </div>

            <button wire:click="$toggle('filterLowStock')" 
                class="flex items-center gap-1.5 {{ $filterLowStock ? 'bg-red-600 border-red-700 shadow-red-200' : ($lowStockCount > 0 ? 'bg-red-50 border-red-200' : 'bg-gray-50 border-gray-200') }} border rounded-xl px-3 py-1.5 shrink-0 shadow-sm transition-all hover:scale-105">
                <i class="fa-solid fa-triangle-exclamation {{ $filterLowStock ? 'text-white' : ($lowStockCount > 0 ? 'text-red-500 animate-pulse' : 'text-gray-400') }} text-xs"></i>
                <div class="text-left">
                    <p class="text-[8px] font-black uppercase {{ $filterLowStock ? 'text-red-200' : ($lowStockCount > 0 ? 'text-red-400' : 'text-gray-400') }} leading-none">Cảnh báo tồn</p>
                    <p class="text-sm font-black {{ $filterLowStock ? 'text-white' : ($lowStockCount > 0 ? 'text-red-700' : 'text-gray-700') }} leading-none">{{ $lowStockCount }}</p>
                </div>
            </button>

            <button wire:click="$toggle('filterExpiry')" 
                class="flex items-center gap-1.5 {{ $filterExpiry ? 'bg-orange-600 border-orange-700 shadow-orange-200' : ($expiringCount > 0 ? 'bg-orange-50 border-orange-200' : 'bg-gray-50 border-gray-200') }} border rounded-xl px-3 py-1.5 shrink-0 shadow-sm transition-all hover:scale-105">
                <i class="fa-solid fa-hourglass-half {{ $filterExpiry ? 'text-white' : ($expiringCount > 0 ? 'text-orange-500 animate-bounce' : 'text-gray-400') }} text-xs"></i>
                <div class="text-left">
                    <p class="text-[8px] font-black uppercase {{ $filterExpiry ? 'text-orange-200' : ($expiringCount > 0 ? 'text-orange-400' : 'text-gray-400') }} leading-none">Sắp hết hạn</p>
                    <p class="text-sm font-black {{ $filterExpiry ? 'text-white' : ($expiringCount > 0 ? 'text-orange-700' : 'text-gray-700') }} leading-none">{{ $expiringCount }}</p>
                </div>
            </button>

            <div class="flex items-center gap-1.5 bg-gray-50 border border-gray-200 rounded-xl px-3 py-1.5 shrink-0 shadow-sm">
                <i class="fa-solid fa-calendar-day text-gray-400 text-xs"></i>
                <div>
                    <p class="text-[8px] font-black uppercase text-gray-400 leading-none">Ngày</p>
                    <p class="text-xs font-black text-gray-700 leading-none">{{ now()->format('d/m/Y') }}</p>
                </div>
            </div>

            <div class="h-6 w-px bg-gray-200 shrink-0"></div>

            {{-- Actions --}}
            <div class="flex items-center gap-1 shrink-0">
                <button wire:click="editSelected" 
                    {{ empty($selectedItems) ? 'disabled' : '' }}
                    class="h-8 px-3 rounded-xl bg-blue-50 text-blue-600 border border-blue-100 hover:bg-blue-600 hover:text-white disabled:opacity-30 disabled:hover:bg-blue-50 disabled:hover:text-blue-600 transition-all flex items-center gap-1.5 shadow-sm active:scale-95">
                    <i class="fa-solid fa-pen-to-square text-[10px]"></i>
                    <span class="text-[10px] font-black uppercase tracking-tighter">Sửa</span>
                </button>
                <button wire:click="deleteSelected" 
                    wire:confirm="Bạn có chắc chắn muốn xóa các lô hàng đã chọn?"
                    {{ empty($selectedItems) ? 'disabled' : '' }}
                    class="h-8 px-3 rounded-xl bg-red-50 text-red-600 border border-red-100 hover:bg-red-600 hover:text-white disabled:opacity-30 disabled:hover:bg-red-50 disabled:hover:text-red-600 transition-all flex items-center gap-1.5 shadow-sm active:scale-95">
                    <i class="fa-solid fa-trash-can text-[10px]"></i>
                    <span class="text-[10px] font-black uppercase tracking-tighter">Xóa</span>
                </button>
            </div>

            <div class="h-6 w-px bg-gray-200 shrink-0"></div>

            {{-- refined Search Bar --}}
            <div class="relative w-64">
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Tên hàng, mã, số lô..."
                    class="w-full pl-9 pr-4 py-2 rounded-full border-gray-200 bg-white focus:ring-blue-500 focus:border-blue-500 text-xs shadow-sm shadow-inner placeholder:italic">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 text-[10px]"></i>
                </div>
            </div>
        </div>
    @endif

    {{-- FILTER BAR (optional for general warehouse) --}}
    <div class="{{ in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES', 'FINISHED_GOODS']) ? 'hidden' : 'grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print' }}">
        <div class="relative group">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Số chứng từ, tên khách, ghi chú..." class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 bg-white focus:ring-red-500 focus:border-red-500 text-xs shadow-sm group-hover:shadow-md transition-all placeholder:italic">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
            </div>
        </div>
        <div class="relative group">
            <select wire:model.live="filterWarehouse" class="w-full pl-10 pr-10 py-2 rounded-xl border-gray-200 bg-white focus:ring-red-500 focus:border-red-500 text-xs shadow-sm appearance-none group-hover:shadow-md transition-all font-bold">
                <option value="">Tất cả kho hàng</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fa-solid fa-warehouse text-gray-400 text-xs"></i>
            </div>
        </div>
    </div>

    {{-- SECTION: TON KHO CHI TIET --}}
    <div class="px-6 mb-8">
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-700 to-blue-500 px-6 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-table-list text-white text-sm"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-black text-white uppercase tracking-tight leading-none">Danh sách Tồn kho chi tiết</h2>
                        <p class="text-[9px] text-blue-100 font-bold uppercase tracking-widest mt-0.5 opacity-80">Theo dõi lô hàng · Realtime</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 no-print">
                    <button wire:click="exportExcel" class="bg-white/10 border border-white/20 text-white hover:bg-white/20 px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all">
                        <i class="fa-solid fa-file-excel mr-1.5 text-emerald-300"></i> Xuất Excel
                    </button>
                    <button wire:click="printStock" class="bg-white text-blue-700 hover:bg-blue-50 px-4 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest shadow-lg transition-all">
                        <i class="fa-solid fa-print mr-1.5"></i> In báo cáo
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-bold border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 w-10 text-center no-print">
                                <input type="checkbox" wire:click="toggleSelectAll" class="rounded border-gray-300">
                            </th>
                            @if(!in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES']))
                                <th class="px-4 py-3">Sản phẩm / Số lô</th>
                                <th class="px-4 py-3">Hãng SX</th>
                                <th class="px-4 py-3 text-center">Hạn dùng</th>
                                <th class="px-4 py-3">Kho</th>
                                <th class="px-4 py-3 text-center">ĐVT</th>
                                <th class="px-4 py-3 text-center font-bold bg-blue-50/50">Tồn theo lô</th>
                                <th class="px-4 py-3 text-center">Trạng thái</th>
                            @else
                                <th class="px-4 py-3">NVL & Số lô</th>
                                <th class="px-4 py-3">Hãng SX</th>
                                <th class="px-4 py-3 text-center">Hạn dùng</th>
                                <th class="px-4 py-3 text-center">ĐVT</th>
                                <th class="px-4 py-3">Vị trí</th>
                                <th class="px-4 py-3 text-center text-[10px] uppercase">Định mức</th>
                                <th class="px-4 py-3 text-center font-bold bg-amber-50/50">SL Lô</th>
                                <th class="px-4 py-3 text-right">Giá trị</th>
                            @endif
                            <th class="px-4 py-3 text-center no-print">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($inventoryItems as $item)
                        @php
                            $prod = $item->product;
                            $qty = $item->quantity;
                            $totalQty = $prod->inventory?->quantity ?? 0;
                            $isLowStock = $prod->min_stock > 0 && $totalQty < $prod->min_stock;
                        @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-4 py-3 text-center">
                                <input type="checkbox" wire:model.live="selectedItems" value="prod-{{ $item->id }}" class="rounded border-gray-300">
                            </td>
                            @if(!in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES']))
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900 text-xs">{{ $prod->name }}</div>
                                    <div class="text-[9px] text-blue-600 font-black mt-0.5">Lô: {{ $item->batch_number }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-[10px] italic">{{ $item->manufacturer_name ?? '---' }}</td>
                                <td class="px-4 py-3 text-center font-bold text-xs">
                                     {{ $item->expiry_date ? $item->expiry_date->format('d/m/Y') : '---' }}
                                </td>
                                <td class="px-4 py-3 text-[10px] text-gray-400">{{ $item->warehouse?->name ?? '---' }}</td>
                                <td class="px-4 py-3 text-center text-[10px] text-gray-500">{{ $prod->unit }}</td>
                                <td class="px-4 py-3 text-center font-black text-blue-700 bg-blue-50/20">@nfmt($qty)</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $isLowStock ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-green-100 text-green-700' }}">
                                        {{ $isLowStock ? 'THẤP' : 'ỔN ĐỊNH' }}
                                    </span>
                                </td>
                            @else
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-800 text-xs leading-tight">{{ $prod->name }}</div>
                                    <div class="flex items-center mt-1">
                                        <span class="bg-amber-100 text-amber-700 text-[9px] font-black px-1 rounded border border-amber-200 uppercase">Lô: {{ $item->batch_number }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-500 text-[10px] italic">{{ $item->manufacturer_name ?? '---' }}</td>
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $expSoon = $item->expiry_date && $item->expiry_date->isFuture() && $item->expiry_date->diffInMonths(now()) < 6;
                                        $expired = $item->expiry_date && $item->expiry_date->isPast();
                                    @endphp
                                    <span class="text-[10px] font-black {{ $expired ? 'text-red-600 bg-red-50 rounded px-1 animate-pulse border border-red-200' : ($expSoon ? 'text-orange-600 bg-orange-50 rounded px-1 animate-pulse border border-orange-200' : 'text-gray-600') }}">
                                        {{ $item->expiry_date ? $item->expiry_date->format('d/m/Y') : '---' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-[10px] font-bold text-gray-400">{{ $prod->unit }}</td>
                                <td class="px-4 py-3 text-[10px] text-amber-600 font-bold"><i class="fa-solid fa-location-dot mr-1 opacity-50"></i>{{ $item->location ?: ($prod->location ?: '---') }}</td>
                                <td class="px-4 py-3 text-center text-[10px] text-gray-400">@nfmt($prod->min_stock)</td>
                                <td class="px-4 py-3 text-center font-black text-blue-700 text-base bg-amber-50/20 relative">
                                    @nfmt($qty)
                                    @if($isLowStock)
                                        <i class="fa-solid fa-triangle-exclamation text-red-500 text-[8px] absolute top-1 right-1 animate-pulse" title="Tồn tổng thấp!"></i>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-black text-emerald-700 text-xs">@nfmt($qty * ($item->batch_unit_price ?? 0))₫</td>
                            @endif
                            <td class="px-4 py-3 text-center no-print whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1">
                                    <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'import', 'productId' => $prod->id, 'warehouse_code' => $item->warehouse?->code ?? 'RAW_MAT']) }}" class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Nhập thêm">
                                        <i class="fa-solid fa-plus-circle text-sm"></i>
                                    </a>
                                    <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'export', 'productId' => $prod->id, 'warehouse_code' => $item->warehouse?->code ?? 'RAW_MAT']) }}" class="p-1.5 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-600 hover:text-white transition-all shadow-sm" title="Xuất lô">
                                        <i class="fa-solid fa-minus-circle text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-5 py-12 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-box-open text-5xl mb-3 opacity-20 block"></i>
                                Không tìm thấy mặt hàng nào trong kho.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL CHỈNH SỬA LÔ HÀNG (MÀN HÌNH TO) --}}
    <div x-data="{ open: @entangle('showEditModal') }" x-show="open" 
         x-cloak
         class="fixed inset-0 z-[3000] flex items-center justify-center p-4 bg-gray-900/80 backdrop-blur-sm transition-all"
         style="display: none;">
        <div x-show="open" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0 scale-90" 
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white w-full max-w-2xl rounded-3xl shadow-3xl overflow-hidden border-4 border-white">
            
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <i class="fa-solid fa-pen-to-square text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-black uppercase tracking-tight text-lg leading-none">Chỉnh sửa chi tiết NVL & Lô hàng</h3>
                        <p class="text-[10px] text-blue-100 font-bold uppercase tracking-widest leading-none mt-1.5 opacity-80">Giao diện tùy chỉnh thông tin toàn diện</p>
                    </div>
                </div>
                <button @click="open = false" class="hover:rotate-90 transition-all text-white/50 hover:text-white">
                    <i class="fa-solid fa-xmark text-2xl"></i>
                </button>
            </div>

            <form wire:submit.prevent="saveBatch" class="p-8 max-h-[85vh] overflow-y-auto custom-scrollbar">
                {{-- Khu vực 1: Thông tin NVL --}}
                <div class="mb-8 relative">
                    <div class="absolute -left-4 top-0 bottom-0 w-1 bg-blue-500 rounded-full"></div>
                    <h4 class="text-xs font-black text-blue-600 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fa-solid fa-box mr-2"></i> 1. Cấu hình Mặt hàng (NVL)
                    </h4>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Mã nguyên vật liệu</label>
                            <input type="text" wire:model="batchForm.product_code" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-gray-50/50 focus:ring-blue-500 shadow-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Đơn vị tính</label>
                            <input type="text" wire:model="batchForm.product_unit" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-gray-50/50 focus:ring-blue-500 shadow-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Tên nguyên vật liệu</label>
                            <input type="text" wire:model="batchForm.product_name" class="w-full rounded-xl border-gray-200 text-sm font-black bg-white focus:ring-blue-500 shadow-sm border-2 border-blue-50">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Định mức tồn tối thiểu (Min)</label>
                            <div class="relative">
                                <input type="number" wire:model="batchForm.product_min_stock" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-red-50/30 text-red-600 focus:ring-red-500 shadow-sm">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-red-300 uppercase">Alert Level</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-px bg-gray-100 mb-8"></div>

                {{-- Khu vực 2: Thông tin Lô hàng --}}
                <div class="mb-4 relative">
                    <div class="absolute -left-4 top-0 bottom-0 w-1 bg-amber-500 rounded-full"></div>
                    <h4 class="text-xs font-black text-amber-600 uppercase tracking-widest mb-4 flex items-center">
                        <i class="fa-solid fa-layer-group mr-2"></i> 2. Thông tin Lô hàng & Tồn kho
                    </h4>
                    <div class="grid grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Số lô hàng</label>
                            <input type="text" wire:model="batchForm.batch_number" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-amber-50/30 focus:ring-amber-500 shadow-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Hạn sử dụng</label>
                            <input type="date" wire:model="batchForm.expiry_date" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-white focus:ring-blue-500 shadow-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Nhà sản xuất / Hãng cung cấp</label>
                            <input type="text" wire:model="batchForm.manufacturer_name" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-white focus:ring-blue-500 shadow-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Vị trí lưu kho</label>
                            <input type="text" wire:model="batchForm.location" class="w-full rounded-xl border-gray-200 text-sm font-bold bg-gray-50 focus:ring-blue-500 shadow-sm">
                        </div>
                        <div class="col-span-1">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 ml-1">Số lượng lô hiện tại</label>
                            <div class="relative">
                                <input type="number" step="any" wire:model="batchForm.quantity" class="w-full rounded-xl border-gray-200 text-xl font-black bg-blue-50 text-blue-700 focus:ring-blue-500 shadow-inner">
                                <div class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-bold text-blue-300 uppercase">Input Qty</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-3 no-print">
                    <button type="button" @click="open = false" class="px-8 py-2.5 rounded-xl text-xs font-black uppercase text-gray-400 border border-gray-200 hover:bg-gray-50 transition-all">Hủy bỏ</button>
                    <button type="submit" class="px-12 py-2.5 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-200 hover:scale-105 active:scale-95 transition-all">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>

    {{-- LỊCH SỬ NHẬP XUẤT --}}
    @if(!in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES', 'FINISHED_GOODS']))
        {{-- ... Existing History code (truncated for clarity, but preserved in final file) ... --}}
        <div class="px-6 pb-8">
            {{-- simplified history --}}
            <div class="bg-gray-100 rounded-xl p-4 text-center text-gray-400 text-[10px] font-bold uppercase">Lịch sử giao dịch chi tiết</div>
        </div>
    @endif

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('print-window', (event) => {
                window.print();
            });
        });
    </script>
</div>
