<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Danh sách Đơn hàng (Sales)</h2>
        <a wire:navigate href="{{ route('orders.create') }}" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-sm transition-colors flex items-center shrink-0">
            <i class="fa-solid fa-cart-plus mr-2"></i> Tạo đơn hàng mới
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-medium border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4">Mã ĐH</th>
                        <th class="px-6 py-4">Khách hàng</th>
                        <th class="px-6 py-4">Ngày đặt</th>
                        <th class="px-6 py-4">Tổng tiền</th>
                        <th class="px-6 py-4 text-center">Trạng thái</th>
                        <th class="px-6 py-4">Người tạo</th>
                        <th class="px-6 py-4 text-center">Hiện Trạng</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono font-medium text-gray-800">#ORD_{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $order->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ $order->customer_phone ?? 'Không có SĐT' }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->order_date ? $order->order_date->format('d/m/Y') : '' }}</td>
                            <td class="px-6 py-4 text-gray-900 font-semibold">@nfmt($order->total_amount) ₫</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = [
                                        'PENDING'       => 'bg-red-100 text-red-700 border-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.6)]', // Nhấp nháy đỏ
                                        'CONFIRMED'     => 'bg-blue-100 text-blue-800 border-blue-200',          // 🔵 Xanh dương 
                                        'IN_PRODUCTION' => 'bg-orange-100 text-orange-800 border-orange-200',    // 🟠 Cam
                                        'READY'         => 'bg-green-100 text-green-700 border-green-200',       // 🟢 Xanh lá sáng
                                        'DELIVERED'     => 'bg-emerald-100 text-emerald-800 border-emerald-300', // 🟢 Xanh đậm
                                        'COMPLETED'     => 'bg-gray-200 text-gray-800 border-gray-300',          // ⚫ Xám
                                        'CANCELLED'     => 'bg-red-100 text-red-800 border-red-200',             // 🔴 Đỏ
                                    ];
                                    
                                    $statusLabels = [
                                        'PENDING'       => 'PENDING (Chờ xác nhận)',
                                        'CONFIRMED'     => 'CONFIRMED (Đã duyệt)',
                                        'IN_PRODUCTION' => 'IN_PRODUCTION (Đang SX)',
                                        'READY'         => 'READY (Sẵn sàng)',
                                        'DELIVERED'     => 'DELIVERED (Đã giao)',
                                        'COMPLETED'     => 'COMPLETED (Hoàn tất)',
                                        'CANCELLED'     => 'CANCELLED (Đã hủy)',
                                    ];

                                    $colorClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                                    $label = $statusLabels[$order->status] ?? $order->status;
                                @endphp
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-md border {{ $colorClass }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->creator?->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center space-y-2">
                                    {{-- Warehouse Feedback Display --}}
                                    <div class="w-full flex items-center justify-center">
                                        @if(in_array($order->status, ['COMPLETED', 'CANCELLED']))
                                            <div class="flex items-center text-gray-400">
                                                <i class="fa-solid fa-circle-check text-[10px] mr-1.5 text-gray-300"></i>
                                                <span class="text-[9px] font-bold uppercase leading-none italic">Đã hoàn tất quy trình</span>
                                            </div>
                                        @elseif($order->warehouse_status === 'sufficient')
                                            <div class="group relative flex items-center">
                                                <span class="flex h-2 w-2 rounded-full bg-green-500 mr-1.5 shadow-[0_0_5px_rgba(34,197,94,0.6)]"></span>
                                                <span class="text-[10px] font-bold text-green-700 uppercase leading-none">Kho: Đủ hàng</span>
                                                @if($order->warehouse_note)
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-white text-gray-800 text-[10px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 shadow-xl border border-blue-200">
                                                        <p class="font-bold border-b border-gray-100 pb-1 mb-1 text-green-600">Ghi chú từ Kho:</p>
                                                        <p class="italic">"{{ $order->warehouse_note }}"</p>
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-white"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($order->warehouse_status === 'insufficient')
                                            <div class="group relative flex items-center animate-bounce-slow">
                                                <span class="flex h-2 w-2 rounded-full bg-red-500 mr-1.5 shadow-[0_0_5px_rgba(239,68,68,0.6)]"></span>
                                                <span class="text-[10px] font-bold text-red-600 uppercase leading-none">Kho: THIẾU HÀNG</span>
                                                @if($order->warehouse_note)
                                                    <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 w-48 p-2 bg-white text-gray-800 text-[10px] rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 shadow-xl border border-red-200">
                                                        <p class="font-bold border-b border-gray-100 pb-1 mb-1 text-red-500">Ghi chú từ Kho:</p>
                                                        <p class="italic text-gray-600">"{{ $order->warehouse_note }}"</p>
                                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-white"></div>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center opacity-40">
                                                <i class="fa-solid fa-hourglass-start text-[10px] mr-1.5"></i>
                                                <span class="text-[9px] font-bold uppercase leading-none italic">Chờ kho...</span>
                                            </div>
                                        @endif
                                    </div>

                                        
                                        @if(!in_array($order->status, ['COMPLETED', 'CANCELLED']))
                                        <div x-data="{ drop: false }" class="relative">
                                            <button @click="drop = !drop" @click.away="drop = false" class="text-[10px] px-2 py-1 bg-white text-gray-600 rounded-md hover:bg-gray-50 border border-gray-300 font-bold transition-all shadow-sm">
                                                <i class="fa-solid fa-ellipsis-vertical"></i>
                                            </button>
                                            <div x-show="drop" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-2xl border border-gray-100 z-30 py-1.5 text-left ring-1 ring-black ring-opacity-5">
                                                <button wire:click="forceStatus({{ $order->id }}, 'COMPLETED')" class="w-full text-left px-3 py-2 text-[10px] text-gray-700 hover:bg-gray-50 font-bold transition-colors">
                                                    <i class="fa-solid fa-circle-check text-green-500 mr-2 w-3"></i> HOÀN TẤT
                                                </button>
                                                <button wire:click="forceStatus({{ $order->id }}, 'CANCELLED')" class="w-full text-left px-3 py-2 text-[10px] text-red-600 hover:bg-red-50 font-bold transition-colors">
                                                    <i class="fa-solid fa-circle-xmark text-red-400 mr-2 w-3"></i> HỦY BỎ
                                                </button>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-receipt text-4xl text-gray-300 mb-3"></i>
                                    <p>Chưa có đơn hàng nào trong hệ thống</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
