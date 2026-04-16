<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Việc cần làm của tôi</h2>
            <p class="text-sm text-gray-500 mt-1">Danh sách các lệnh sản xuất đang phụ trách.</p>
        </div>
        <div class="bg-primary/10 text-primary px-4 py-2 rounded-xl font-bold text-sm">
            <i class="fa-solid fa-user-circle mr-2"></i> {{ Auth::user()->name }}
        </div>
    </div>

    @if($myOrders->isEmpty())
        <div class="bg-white rounded-2xl border border-dashed border-gray-300 py-20 text-center">
            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-mug-hot text-3xl text-gray-300"></i>
            </div>
            <p class="text-gray-500 font-medium">Hiện tại bạn chưa được bàn giao lệnh sản xuất nào mới.</p>
            <p class="text-xs text-gray-400 mt-1">Chúc bạn một ngày làm việc vui vẻ!</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($myOrders as $po)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                    <div class="p-5 border-b border-gray-50">
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-0.5 rounded uppercase">#Lệnh {{ $po->id }}</span>
                            @if($po->status === 'qc')
                                <span class="text-[10px] font-bold text-purple-600 bg-purple-50 px-2 py-0.5 rounded uppercase">Đang chờ QC</span>
                            @else
                                <span class="animate-pulse text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-0.5 rounded uppercase italic">Đang thực hiện</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 text-lg leading-tight">{{ $po->product?->name }}</h3>
                        <div class="mt-4 flex items-center justify-between">
                            <div class="text-center bg-gray-50 rounded-lg px-4 py-2">
                                <span class="block text-[10px] text-gray-400 uppercase">Cần làm</span>
                                <span class="block text-xl font-black text-gray-800">{{ $po->quantity }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] text-gray-400 uppercase italic">Hạn hoàn thành</span>
                                <span class="block text-sm font-bold text-red-500 italic">{{ $po->end_date ? $po->end_date->format('d/m/Y') : 'Không có' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-gray-50/50 flex-1">
                        <div class="space-y-3">
                            <div class="flex items-start text-sm text-gray-600">
                                <i class="fa-solid fa-quote-left text-gray-300 mr-2 mt-1"></i>
                                <span class="italic text-xs">{{ $po->note ?: 'Không có ghi chú đặc biệt.' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-5 bg-white border-t border-gray-100">
                        @if($po->status === 'in_progress')
                            <button 
                                wire:click="updateProgress({{ $po->id }}, 'qc')" 
                                class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-purple-200 flex items-center justify-center">
                                <i class="fa-solid fa-paper-plane mr-2"></i> HOÀN THÀNH - GỬI QC
                            </button>
                        @elseif($po->status === 'qc')
                            <div class="text-center py-2 text-purple-600 font-bold text-sm bg-purple-50 rounded-xl">
                                <i class="fa-solid fa-spinner fa-spin mr-2"></i> Đang chờ xác nhận từ QC
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
