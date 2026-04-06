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
                                                {{-- Sales Orders --}}
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

                                                {{-- Production Orders --}}
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

            <button onclick="window.print()" class="bg-gray-900 border border-gray-900 text-white hover:bg-black px-4 py-1.5 rounded-lg shadow-md transition-all flex items-center text-xs font-black uppercase tracking-widest">
                <i class="fa-solid fa-print mr-2 {{ count($selectedItems) > 0 || count($selectedTransactions) > 0 ? 'text-yellow-400 animate-bounce' : '' }}"></i> 
                @if(count($selectedItems) > 0 || count($selectedTransactions) > 0)
                    IN ĐÃ CHỌN ({{ count($selectedItems) + count($selectedTransactions) }})
                @else
                    IN DANH SÁCH
                @endif
            </button>
            @if(count($selectedItems) > 0 || count($selectedTransactions) > 0)
                <button wire:click="clearSelected(); clearSelectedTransactions();" class="text-xs text-red-500 hover:text-red-700 font-bold uppercase tracking-wider">
                    Bỏ chọn
                </button>
            @endif
        </div>
    </div>

    {{-- FILTER BAR --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 no-print">
        {{-- Search Bar --}}
        <div class="relative group">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Số chứng từ, tên khách, ghi chú..." class="w-full pl-10 pr-4 py-2 rounded-xl border-gray-200 bg-white focus:ring-red-500 focus:border-red-500 text-xs shadow-sm group-hover:shadow-md transition-all placeholder:italic">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400 text-xs"></i>
            </div>
        </div>

        {{-- Warehouse Filter --}}
        <div class="relative group">
            <select wire:model.live="warehouseId" class="w-full pl-10 pr-10 py-2 rounded-xl border-gray-200 bg-white focus:ring-red-500 focus:border-red-500 text-xs shadow-sm appearance-none group-hover:shadow-md transition-all font-bold">
                <option value="">Tất cả kho hàng</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                @endforeach
            </select>
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fa-solid fa-warehouse text-gray-400 text-xs"></i>
            </div>
        </div>

        {{-- Transaction Type Filter --}}
        <div class="relative group">
            <select wire:model.live="transactionType" class="w-full pl-10 pr-10 py-2 rounded-xl border-gray-200 bg-white focus:ring-red-500 focus:border-red-500 text-xs shadow-sm appearance-none group-hover:shadow-md transition-all font-bold">
                <option value="">Tất cả nghiệp vụ</option>
                <option value="IMPORT">Nhập kho</option>
                <option value="EXPORT">Xuất kho</option>
                <option value="RETURN">Hàng trả lại</option>
                <option value="ADJUSTMENT">Điều chỉnh</option>
            </select>
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i class="fa-solid fa-right-left text-gray-400 text-xs"></i>
            </div>
        </div>

        {{-- Action Summary (Compact) --}}
        <div class="bg-gray-100/80 rounded-xl px-4 py-2 flex items-center justify-between border border-gray-200 shadow-inner">
            <div class="flex flex-col">
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-tighter">Tổng phát sinh</span>
                <span class="text-xs font-black text-gray-900">{{ number_format($transactions->count()) }} dòng</span>
            </div>
            <i class="fa-solid fa-chart-line text-gray-400 opacity-30"></i>
        </div>
    </div>

    {{-- SECTION 1: TỒN KHO --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fa-solid fa-boxes-stacked text-blue-500 mr-2"></i> Tồn kho hiện tại
            </h3>
            <span class="text-xs text-gray-400">{{ $inventoryItems->count() }} mặt hàng</span>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 w-10 text-center">
                                <i class="fa-solid fa-check-double text-gray-400"></i>
                            </th>
                            @if(!in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES']))
                                <th class="px-5 py-3">Mã SP</th>
                            @endif
                            <th class="px-5 py-3">Tên Hàng hóa</th>
                            <th class="px-5 py-3">Kho</th>
                            <th class="px-5 py-3">ĐVT</th>
                            <th class="px-5 py-3 text-center">Min</th>
                            <th class="px-5 py-3 text-center">Max</th>
                            <th class="px-5 py-3 text-center font-bold bg-blue-50/50">Tồn hiện tại</th>
                            <th class="px-5 py-3 text-center">Trạng thái</th>
                            <th class="px-5 py-3 text-center no-print">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($inventoryItems as $prod)
                        @php
                            $qty = $prod->inventory?->quantity ?? 0;
                            if ($prod->min_stock > 0 && $qty < $prod->min_stock) {
                                $badge = 'text-red-700 bg-red-100 border-red-300 animate-pulse';
                                $badgeText = '⚠ CẠN KHO';
                            } elseif ($prod->max_stock > 0 && $qty > $prod->max_stock) {
                                $badge = 'text-orange-700 bg-orange-100 border-orange-300';
                                $badgeText = '⬆ VƯỢT MỨC';
                            } else {
                                $badge = 'text-green-700 bg-green-50 border-green-200';
                                $badgeText = '✓ Ổn định';
                            }
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors {{ in_array('prod-' . $prod->id, $selectedItems) ? 'bg-blue-50/30' : '' }}">
                            <td class="px-5 py-3 text-center">
                                <input type="checkbox" wire:model.live="selectedItems" value="prod-{{ $prod->id }}" class="rounded border-gray-300 text-primary shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </td>
                            @if(!in_array($this->getSelectedWarehouseCode(), ['RAW_MAT', 'SUPPLIES']))
                                <td class="px-5 py-3 font-mono text-gray-500 text-xs">{{ $prod->code }}</td>
                            @endif
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $prod->name }}</td>                            <td class="px-5 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded bg-gray-100 text-gray-600 border border-gray-200">
                                    {{ $prod->warehouse?->name ?? '---' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-gray-500">{{ $prod->unit }}</td>
                            <td class="px-5 py-3 text-center text-gray-400">{{ number_format($prod->min_stock) }}</td>
                            <td class="px-5 py-3 text-center text-gray-400">{{ $prod->max_stock ? number_format($prod->max_stock) : '∞' }}</td>
                            <td class="px-5 py-3 text-center font-bold text-lg {{ $qty < $prod->min_stock ? 'text-red-600' : 'text-blue-700' }}">
                                {{ number_format($qty) }}
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2 py-1 text-xs font-semibold rounded-md border {{ $badge }}">{{ $badgeText }}</span>
                            </td>
                            <td class="px-5 py-3 text-center no-print whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-1">
                                    <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'import', 'warehouse_code' => $prod->warehouse?->code ?? 'RAW_MAT', 'productId' => $prod->id]) }}" 
                                       class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors" title="Nhập kho mặt hàng này">
                                        <i class="fa-solid fa-plus-circle"></i> Nhập
                                    </a>
                                    <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'export', 'warehouse_code' => $prod->warehouse?->code ?? 'RAW_MAT', 'productId' => $prod->id]) }}" 
                                       class="p-1.5 text-orange-600 hover:bg-orange-50 rounded-md transition-colors" title="Xuất kho mặt hàng này">
                                        <i class="fa-solid fa-minus-circle"></i> Xuất
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-5 py-8 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                                Không tìm thấy sản phẩm nào trong kho.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- SECTION 2: LỊCH SỬ NHẬP / XUẤT --}}
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fa-solid fa-right-left text-orange-500 mr-2"></i> Lịch sử Nhập / Xuất kho
                @if(count($selectedTransactions) > 0)
                    <span class="ml-3 text-sm font-normal text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full border border-blue-100">
                         Đã chọn {{ count($selectedTransactions) }}
                    </span>
                    <button wire:click="clearSelectedTransactions" class="ml-2 text-[10px] text-red-500 hover:text-red-700 font-bold uppercase tracking-wider">
                        Bỏ chọn
                    </button>
                @endif
            </h3>
            <span class="text-xs text-gray-400">{{ $transactions->count() }} giao dịch</span>
        </div>

        {{-- HISTORY FILTER BAR --}}
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 mb-4 flex flex-wrap items-center gap-4 no-print">
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-tight">Từ ngày:</label>
                <input type="date" wire:model.live="historyFromDate" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 py-1">
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-tight">Đến ngày:</label>
                <input type="date" wire:model.live="historyToDate" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 py-1">
            </div>
            <div class="flex items-center gap-2 border-l border-gray-200 pl-4">
                <label class="text-xs font-bold text-gray-500 uppercase tracking-tight">Loại:</label>
                <select wire:model.live="historyType" class="text-xs rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 py-1">
                    <option value="">Tất cả giao dịch</option>
                    <option value="import">Chỉ Nhập kho (+)</option>
                    <option value="export">Chỉ Xuất kho (-)</option>
                </select>
            </div>
            @if($historyFromDate || $historyToDate || $historyType)
                <button wire:click="clearHistoryFilters" class="text-[10px] bg-white border border-gray-200 px-2 py-1 rounded text-gray-500 hover:text-red-600 hover:border-red-200 transition-colors">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Xóa lọc
                </button>
            @endif
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 w-10 text-center no-print">
                                <i class="fa-solid fa-check-double text-gray-400"></i>
                            </th>
                            <th class="px-4 py-3">Ngày thực hiện</th>
                            <th class="px-4 py-3">Loại</th>
                            <th class="px-4 py-3">Hàng hóa</th>
                            <th class="px-4 py-3">Kho</th>
                            <th class="px-4 py-3 text-center">SL</th>
                            <th class="px-4 py-3">Đơn giá</th>
                            <th class="px-4 py-3">Đối tác</th>
                            <th class="px-4 py-3 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($transactions as $txn)
                        <tr class="hover:bg-gray-50 transition-colors {{ in_array('txn-' . $txn->id, $selectedTransactions) ? 'bg-orange-50/30' : '' }}">
                            <td class="px-4 py-3 text-center no-print border-r border-gray-50/50">
                                <input type="checkbox" wire:model.live="selectedTransactions" value="txn-{{ $txn->id }}" class="rounded border-gray-300 text-orange-500 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50">
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ $txn->transaction_date ? $txn->transaction_date->format('d/m/Y') : $txn->created_at->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-gray-400">Lúc: {{ $txn->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-4 py-3">
                                @if($txn->type === 'import')
                                    <span class="inline-flex items-center text-xs font-semibold text-blue-700 bg-blue-50 px-2 py-1 rounded border border-blue-200">
                                        <i class="fa-solid fa-arrow-down mr-1"></i> NHẬP
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-xs font-semibold text-orange-700 bg-orange-50 px-2 py-1 rounded border border-orange-200">
                                        <i class="fa-solid fa-arrow-up mr-1"></i> XUẤT
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="font-medium text-gray-900 text-xs">{{ $txn->product?->name }}</div>
                                @if($txn->product?->warehouse?->code === 'FINISHED_GOODS')
                                    <div class="text-[10px] text-gray-400 font-mono">{{ $txn->product?->code }}</div>
                                @endif
                            </td>                            <td class="px-4 py-3">
                                <span class="text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">{{ $txn->product?->warehouse?->name ?? '---' }}</span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold {{ $txn->type === 'import' ? 'text-blue-600' : 'text-orange-600' }}">
                                {{ $txn->type === 'import' ? '+' : '-' }}{{ number_format($txn->quantity) }}
                            </td>
                            <td class="px-4 py-3 text-gray-700">{{ $txn->unit_price ? number_format($txn->unit_price, 0, ',', '.') . ' ₫' : '---' }}</td>
                            <td class="px-4 py-3 text-gray-800">
                                {{ $txn->partner_name ?? '---' }}
                                @if($txn->partner_phone) <div class="text-[10px] text-gray-400">{{ $txn->partner_phone }}</div> @endif
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="cancelTransaction({{ $txn->id }})" wire:confirm="Bạn có chắc muốn HỦY giao dịch này? Tồn kho sẽ được hoàn lại." class="text-xs px-2.5 py-1.5 text-red-600 bg-red-50 hover:bg-red-100 rounded-md border border-red-200 font-medium transition-colors" title="Hủy giao dịch này">
                                    <i class="fa-solid fa-xmark mr-1"></i> Hủy
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-400">
                                <i class="fa-solid fa-clipboard-list text-3xl mb-2 block"></i>
                                Chưa có giao dịch nhập / xuất nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
