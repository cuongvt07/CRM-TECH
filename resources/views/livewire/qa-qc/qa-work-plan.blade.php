<div class="p-6 bg-slate-50 min-h-screen">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Kế hoạch làm việc QA/QC</h1>
            <p class="text-slate-500 text-sm">Giám sát và kiểm soát chất lượng từ đầu vào đến sản xuất</p>
        </div>
    </div>

    {{-- Tab Navigation --}}
    <div class="mb-6 flex space-x-1 p-1 bg-slate-200/50 rounded-xl w-fit">
        <button wire:click="setTab('incoming')" class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $activeTab === 'incoming' ? 'bg-white text-emerald-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            <i class="fa-solid fa-truck-ramp-box mr-2"></i> CHECKLIST HÀNG NHẬP
        </button>
        <button wire:click="setTab('production')" class="px-6 py-2 rounded-lg text-sm font-bold transition-all {{ $activeTab === 'production' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}">
            <i class="fa-solid fa-industry mr-2"></i> CHECKLIST SẢN XUẤT
        </button>
    </div>

    @if($activeTab === 'incoming')
        {{-- Checklist Hàng Nhập (RMQC) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-600 font-black border-b border-slate-200 uppercase tracking-tighter text-[11px]">
                    <tr>
                        <th class="px-6 py-4">Mã Phiếu/Ngày</th>
                        <th class="px-6 py-4">Nguyên Vật Liệu</th>
                        <th class="px-6 py-4 text-center">SL Nhập</th>
                        <th class="px-6 py-4">Số Lô / Hạn Dùng</th>
                        <th class="px-6 py-4 text-center">Hiện Trạng</th>
                        <th class="px-6 py-4 text-center">Tình Trạng</th>
                        <th class="px-6 py-4 text-right">Thao Tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($incomingTransactions as $trx)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $trx->voucher_no ?: 'PNK-' . str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-[10px] text-slate-400">{{ $trx->transaction_date ? $trx->transaction_date->format('d/m/Y') : $trx->created_at->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-black text-slate-800">{{ $trx->product?->name }}</div>
                                <div class="text-[10px] font-mono text-emerald-600">{{ $trx->product?->code }}</div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-2 py-1 bg-slate-100 rounded-lg font-bold text-slate-700">+@nfmt($trx->quantity) <small class="text-slate-400">{{ $trx->product?->unit }}</small></span>
                            </td>
                            <td class="px-6 py-4">
                                @if($trx->batch_number)
                                    <div class="text-xs font-bold text-slate-700"><i class="fa-solid fa-barcode mr-1 text-slate-300"></i>{{ $trx->batch_number }}</div>
                                @else
                                    <div class="text-xs text-slate-300 italic">Chưa có số lô</div>
                                @endif
                                @if($trx->expiry_date)
                                    <div class="text-[10px] text-rose-500 font-bold mt-0.5"><i class="fa-solid fa-calendar-xmark mr-1"></i>HSD: {{ \Carbon\Carbon::parse($trx->expiry_date)->format('d/m/Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->qa_inspection_status === 'pending')
                                    <button wire:click="startInspecting({{ $trx->id }})" class="px-2.5 py-1 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg text-xs font-bold border border-red-100 transition-all">
                                        <i class="fa-solid fa-clock mr-1"></i> Chưa kiểm tra
                                    </button>
                                @else
                                    <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-lg text-xs font-bold border border-yellow-200">
                                        <i class="fa-solid fa-magnifying-glass mr-1 animate-pulse"></i> Đang kiểm tra
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($trx->qa_status === 'pending')
                                    <span class="text-[10px] font-black text-slate-300 uppercase">Chưa duyệt</span>
                                @elseif($trx->qa_status === 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100"><i class="fa-solid fa-circle-check mr-1"></i> ĐẠT</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold border border-rose-100"><i class="fa-solid fa-circle-xmark mr-1"></i> KHÔNG ĐẠT</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button wire:click="openIncomingModal({{ $trx->id }})" class="px-4 py-1.5 bg-slate-800 text-white rounded-lg text-[11px] font-black uppercase hover:bg-emerald-600 transition-all shadow-sm">
                                    Thẩm định
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fa-solid fa-folder-open text-4xl text-slate-200 mb-3"></i>
                                    <p class="text-slate-400 font-bold uppercase tracking-widest text-xs">Không có kiện hàng nào cần kiểm tra</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        {{-- Checklist Sản Xuất (IPQC) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($orders as $order)
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col h-full">
                    <!-- Header -->
                    <div class="px-5 py-3 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                        <div>
                            <div class="flex items-center space-x-2">
                                <span class="text-[10px] font-black text-slate-400">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="font-bold text-slate-800 leading-none">{{ $order->product?->name }}</h3>
                            </div>
                            <div class="mt-1 flex items-center space-x-2">
                                <span class="px-1.5 py-0.5 rounded bg-blue-50 text-blue-600 text-[9px] font-bold border border-blue-100 uppercase italic">
                                    S.Lượng: @nfmt($order->quantity)
                                </span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[9px] font-black {{ $order->status === 'qc' ? 'bg-purple-100 text-purple-700' : 'bg-orange-100 text-orange-700' }} uppercase tracking-tighter">
                            {{ $order->status === 'qc' ? 'ĐANG KIỂM QC' : 'ĐANG SẢN XUẤT' }}
                        </span>
                    </div>

                    <!-- Checklist Area -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-xs font-black text-slate-700 uppercase tracking-widest"><i class="fa-solid fa-clipboard-check mr-2 text-emerald-500"></i> Danh mục kiểm tra</h4>
                            @php
                                $completedCount = $order->qaChecklists->where('is_completed', true)->count();
                                $totalCount = $order->qaChecklists->count();
                                $percent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                            @endphp
                            <span class="text-[10px] font-bold text-slate-500">{{ $completedCount }}/{{ $totalCount }} hoàn thành ({{ $percent }}%)</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-slate-100 h-1.5 rounded-full mb-6 overflow-hidden">
                            <div class="bg-emerald-500 h-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>

                        <!-- Task List -->
                        <div class="space-y-3 mb-6 flex-1 max-h-[300px] overflow-y-auto pr-2 customize-scrollbar">
                            @forelse($order->qaChecklists as $task)
                                <div class="flex items-center justify-between group p-2 rounded-lg hover:bg-slate-50 transition-all border border-transparent hover:border-slate-100">
                                    <div class="flex items-center space-x-3">
                                        <button wire:click="toggleTask({{ $task->id }})" class="w-5 h-5 rounded border-2 flex items-center justify-center transition-all {{ $task->is_completed ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-300 hover:border-emerald-400' }}">
                                            @if($task->is_completed) <i class="fa-solid fa-check text-[10px]"></i> @endif
                                        </button>
                                        <span class="{{ $task->is_completed ? 'text-slate-400 line-through' : 'text-slate-700' }} text-sm font-medium">{{ $task->task_name }}</span>
                                    </div>
                                    <button wire:click="deleteTask({{ $task->id }})" class="opacity-0 group-hover:opacity-100 p-1 text-slate-300 hover:text-red-500 transition-all">
                                        <i class="fa-solid fa-trash-can text-xs"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="py-8 text-center bg-slate-50 rounded-xl border-2 border-dashed border-slate-200">
                                    <i class="fa-solid fa-tasks text-slate-300 mb-2 text-xl"></i>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Chưa có đầu việc kiểm tra nào</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Add New Task -->
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <form wire:submit.prevent="addTask({{ $order->id }})" class="flex space-x-2">
                                <input 
                                    type="text" 
                                    wire:model="newTaskName.{{ $order->id }}" 
                                    placeholder="Soạn đầu việc kiểm tra mới..." 
                                    class="flex-1 px-3 py-2 bg-slate-50 border-none rounded-lg text-sm focus:ring-2 focus:ring-emerald-500/20 placeholder-slate-400"
                                >
                                <button type="submit" class="p-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-all shadow-sm shadow-emerald-200">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Footer / Assignee -->
                    <div class="px-5 py-2.5 bg-slate-50/30 border-t border-slate-50 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-black text-slate-500">
                                {{ substr($order->assignee?->name ?? '?', 0, 1) }}
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 italic">Thực hiện: {{ $order->assignee?->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Modal Thẩm Định Hàng Nhập --}}
    @if($showIncomingModal)
        <div class="fixed inset-0 z-[1002] overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4 text-center">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" wire:click="closeIncomingModal"></div>
                <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full border border-slate-100 z-10">
                    <div class="bg-emerald-600 px-6 py-4 flex justify-between items-center text-white">
                        <h3 class="text-lg font-black uppercase tracking-tighter">
                            <i class="fa-solid fa-shield-check mr-2"></i> THẨM ĐỊNH CHẤT LƯỢNG LÔ HÀNG
                        </h3>
                        <button wire:click="closeIncomingModal" class="text-white/80 hover:text-white transition-colors">
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <div class="px-6 py-6 space-y-5">
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Kết quả kiểm định (Duyệt SX) *</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button wire:click="$set('qa_status', 'approved')" class="py-3 px-4 rounded-xl border-2 transition-all flex flex-col items-center justify-center space-y-1 {{ $qa_status === 'approved' ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-slate-100 bg-slate-50 text-slate-400 hover:border-emerald-200' }}">
                                    <i class="fa-solid fa-check-double text-xl"></i>
                                    <span class="text-xs font-bold uppercase">Đạt / Duyệt SX</span>
                                </button>
                                <button wire:click="$set('qa_status', 'rejected')" class="py-3 px-4 rounded-xl border-2 transition-all flex flex-col items-center justify-center space-y-1 {{ $qa_status === 'rejected' ? 'border-rose-500 bg-rose-50 text-rose-700' : 'border-slate-100 bg-slate-50 text-slate-400 hover:border-rose-200' }}">
                                    <i class="fa-solid fa-ban text-xl"></i>
                                    <span class="text-xs font-bold uppercase">Không đạt</span>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Ghi chú lý do / Kết quả chi tiết</label>
                            <textarea wire:model="qa_note" rows="3" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 outline-none transition-all text-sm" placeholder="Ghi nhận các sai sót hoặc kết quả phân tích mẫu..."></textarea>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex justify-end space-x-3">
                        <button wire:click="closeIncomingModal" class="px-5 py-2 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-100 font-bold transition-all text-xs">
                            HỦY BỎ
                        </button>
                        <button wire:click="saveIncomingApproval" class="px-8 py-2 bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 font-black transition-all text-xs uppercase tracking-widest">
                            LƯU KẾT QUẢ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .customize-scrollbar::-webkit-scrollbar { width: 4px; }
        .customize-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .customize-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</div>
