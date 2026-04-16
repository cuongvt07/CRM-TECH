<div class="p-6 bg-slate-50 min-h-screen">
    <!-- Breadcrumb & Header -->
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-2">
            <i class="fa-solid fa-arrow-right-arrow-left text-orange-500 text-xl"></i>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Lịch sử Nhập / Xuất kho</h1>
            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-500 text-[10px] font-bold ml-2">{{ $transactions->total() }} giao dịch</span>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Từ ngày</label>
                <input type="date" wire:model.live="fromDate" class="w-full h-10 px-3 bg-slate-50 border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Đến ngày</label>
                <input type="date" wire:model.live="toDate" class="w-full h-10 px-3 bg-slate-50 border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Loại</label>
                <select wire:model.live="type" class="w-full h-10 px-3 bg-slate-50 border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                    <option value="">Tất cả giao dịch</option>
                    <option value="import">Chỉ Nhập kho</option>
                    <option value="export">Chỉ Xuất kho</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button wire:click="$refresh" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-200 transition-all uppercase tracking-widest">
                    <i class="fa-solid fa-rotate mr-1"></i> Làm mới
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4 w-4">
                            <input type="checkbox" class="rounded border-slate-300">
                        </th>
                        <th class="px-6 py-4">Ngày thực hiện</th>
                        <th class="px-6 py-4 text-center">Loại</th>
                        <th class="px-6 py-4">Hàng hóa</th>
                        <th class="px-6 py-4">Kho</th>
                        <th class="px-6 py-4 text-center">SL</th>
                        <th class="px-6 py-4 text-right">Đơn giá</th>
                        <th class="px-6 py-4">Đối tác</th>
                        <th class="px-6 py-4 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $t)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="rounded border-slate-300">
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-700">{{ $t->transaction_date->format('d/m/Y') }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">Lúc {{ $t->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @if($t->type === 'import')
                                        <span class="px-4 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black border border-blue-100 shadow-sm shadow-blue-50 flex items-center">
                                            <i class="fa-solid fa-arrow-down mr-1.5 opacity-70"></i> NHẬP
                                        </span>
                                    @else
                                        <span class="px-4 py-1 rounded-lg bg-orange-50 text-orange-600 text-[10px] font-black border border-orange-100 shadow-sm shadow-orange-50 flex items-center">
                                            <i class="fa-solid fa-arrow-up mr-1.5 opacity-70"></i> XUẤT
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800">{{ $t->product?->name }}</div>
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">{{ $t->product?->code }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-bold text-slate-500 italic">
                                    {{ $t->product?->warehouse?->name ?? '---' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="text-base font-black {{ $t->type === 'import' ? 'text-blue-600' : 'text-orange-600' }}">
                                    {{ $t->type === 'import' ? '+' : '-' }}@nfmt($t->quantity)
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-black text-slate-700">
                                    @nfmt($t->unit_price) đ
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs font-medium text-slate-500">
                                    {{ $t->partner_name ?? '---' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button 
                                    wire:click="deleteTransaction({{ $t->id }})" 
                                    wire:confirm="Bạn có chắc chắn muốn HỦY giao dịch này? Tồn kho sẽ được hoàn tác lại."
                                    class="px-3 py-1 bg-red-50 text-red-500 rounded-lg text-[10px] font-black border border-red-100 hover:bg-red-500 hover:text-white transition-all uppercase tracking-widest opacity-0 group-hover:opacity-100"
                                >
                                    <i class="fa-solid fa-xmark mr-1"></i> Hủy
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-layer-group text-slate-200 text-4xl mb-4"></i>
                                    <p class="text-slate-400 text-sm font-medium">Không tìm thấy giao dịch nào trong khoảng thời gian này.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
