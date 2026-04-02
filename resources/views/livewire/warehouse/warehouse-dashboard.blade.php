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
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800">Quản lý Kho</h2>
            <p class="text-gray-500 mt-1">Nhập kho · Xuất kho · Tồn kho — 3 kho: Nguyên liệu, Vật tư, Thành phẩm</p>
        </div>
        <div class="flex items-center space-x-3 mb-4 sm:mb-0 no-print">
            <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center text-sm font-medium">
                <i class="fa-solid fa-print mr-2 {{ count($selectedItems) > 0 || count($selectedTransactions) > 0 ? 'text-primary animate-bounce' : '' }}"></i> 
                @if(count($selectedItems) > 0 || count($selectedTransactions) > 0)
                    In danh sách đã chọn ({{ count($selectedItems) + count($selectedTransactions) }})
                @else
                    In danh sách
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Tìm theo Mã SP, Tên hàng, NCC, Số HĐ..." class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
        </div>
        <select wire:model.live="filterWarehouse" class="rounded-lg border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm min-w-[200px]">
            <option value="">Tất cả Kho</option>
            @foreach($warehouses as $wh)
                <option value="{{ $wh->id }}">{{ $wh->name }}</option>
            @endforeach
        </select>
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
