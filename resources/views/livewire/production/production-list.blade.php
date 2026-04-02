<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Quản lý Sản xuất (Production)</h2>
            <p class="text-sm text-gray-500 mt-1">Điều phối lệnh sản xuất từ Đơn hàng bán.</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-2">
            <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-sm font-medium transition-colors">
                <i class="fa-solid fa-print mr-2"></i> In kế hoạch
            </button>
        </div>
    </div>

    <!-- TABS STATUS -->
    <div class="flex border-b border-gray-200 bg-white rounded-t-xl overflow-hidden shadow-sm">
        <button wire:click="setTab('pending')" class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors {{ $activeTab === 'pending' ? 'text-primary border-primary bg-blue-50/50' : 'text-gray-500 border-transparent hover:bg-gray-50' }}">
            <i class="fa-solid fa-clock mr-2"></i> CHỜ XỬ LÝ
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-[10px]">{{ \App\Models\ProductionOrder::where('status', 'pending')->count() }}</span>
        </button>
        <button wire:click="setTab('in_progress')" class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors {{ $activeTab === 'in_progress' ? 'text-orange-600 border-orange-600 bg-orange-50/50' : 'text-gray-500 border-transparent hover:bg-gray-50' }}">
            <i class="fa-solid fa-gears mr-2"></i> ĐANG LÀM
            <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 rounded-full text-[10px]">{{ \App\Models\ProductionOrder::where('status', 'in_progress')->count() }}</span>
        </button>
        <button wire:click="setTab('qc')" class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors {{ $activeTab === 'qc' ? 'text-purple-600 border-purple-600 bg-purple-50/50' : 'text-gray-500 border-transparent hover:bg-gray-50' }}">
            <i class="fa-solid fa-microscope mr-2"></i> KIỂM TRA (QC)
            <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full text-[10px]">{{ \App\Models\ProductionOrder::where('status', 'qc')->count() }}</span>
        </button>
        <button wire:click="setTab('completed')" class="flex-1 py-4 text-sm font-bold border-b-2 transition-colors {{ $activeTab === 'completed' ? 'text-green-600 border-green-600 bg-green-50/50' : 'text-gray-500 border-transparent hover:bg-gray-50' }}">
            <i class="fa-solid fa-check-double mr-2"></i> HOÀN THÀNH
            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 rounded-full text-[10px]">{{ \App\Models\ProductionOrder::where('status', 'completed')->count() }}</span>
        </button>
    </div>

    <!-- PRODUCTION LIST -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($productionOrders as $po)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow flex flex-col group">
                <!-- Header -->
                <div class="p-4 border-b border-gray-50 flex justify-between items-start">
                    <div>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $activeTab === 'pending' ? 'bg-gray-100 text-gray-600' : ($activeTab === 'in_progress' ? 'bg-orange-100 text-orange-700' : ($activeTab === 'qc' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700')) }}">
                                #Lệnh {{ $po->id }}
                            </span>
                            @if($po->order_id)
                                <span class="text-[10px] text-primary font-bold">Đơn hàng #{{ $po->order_id }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-gray-900 mt-2 text-lg">{{ $po->product?->name }}</h3>
                        
                        <!-- Material Status Badge -->
                        @if($po->status === 'pending' || $po->status === 'in_progress')
                            @php $matStatus = $po->getMaterialStatus(); @endphp
                            <div class="mt-2">
                                @if($matStatus['status'] === 'sufficient')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-50 text-green-700 border border-green-200">
                                        <i class="fa-solid fa-check-circle mr-1"></i> ĐỦ VẬT TƯ
                                    </span>
                                @elseif($matStatus['status'] === 'insufficient')
                                    <div class="group relative inline-block">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-700 border border-red-200 cursor-help">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i> THIẾU VẬT TƯ
                                        </span>
                                        <!-- Tooltip Material List -->
                                        <div class="invisible group-hover:visible absolute z-10 w-48 bg-white border border-gray-100 shadow-xl rounded-lg p-3 text-[10px] -left-2 mt-1">
                                            <p class="font-bold border-b border-gray-50 pb-1 mb-1 text-red-600 uppercase italic">Vật tư đang thiếu:</p>
                                            <ul class="space-y-1">
                                                @foreach($matStatus['missing'] as $m)
                                                    <li class="flex justify-between border-b border-gray-50/50 pb-1 last:border-0 italic">
                                                        <span>{{ $m['name'] }}:</span>
                                                        <span class="font-bold text-red-500">-{{ number_format($m['shortage'], 1) }} {{ $m['unit'] }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="bg-gray-50 p-2 rounded-lg text-center min-w-[60px]">
                        <span class="block text-xs text-gray-400 uppercase">Số lượng</span>
                        <span class="block text-xl font-black text-gray-800">{{ $po->quantity }}</span>
                    </div>
                </div>

                <!-- Info -->
                <div class="p-4 flex-1 space-y-3">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fa-solid fa-user-gear w-6"></i>
                        <span>Phụ trách: <span class="font-medium text-gray-900">{{ $po->assignee?->name ?? 'Chưa phân công' }}</span></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="fa-solid fa-calendar-day w-6"></i>
                        <span>Ngày tạo: {{ $po->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($po->note)
                        <div class="text-[11px] bg-gray-50 p-2 rounded text-gray-500 border-l-2 border-primary/30">
                            {{ $po->note }}
                        </div>
                    @endif
                </div>

                <!-- Footer Actions -->
                <div class="p-3 bg-gray-50 border-t border-gray-100 mt-auto">
                    @if($po->status === 'pending')
                        @php $isShort = $po->getMaterialStatus()['status'] === 'insufficient'; @endphp
                        <button 
                            wire:click="updateStatus({{ $po->id }}, 'in_progress')" 
                            @if($isShort) wire:confirm="CẢNH BÁO: Kho không đủ nguyên liệu để sản xuất lệnh này. Bạn vẫn muốn tiếp tục chứ?" @endif
                            class="w-full py-2 {{ $isShort ? 'bg-red-500 hover:bg-red-600' : 'bg-orange-500 hover:bg-orange-600' }} text-white rounded-lg text-sm font-bold transition-colors shadow-sm">
                            BẮT ĐẦU SẢN XUẤT
                        </button>
                    @elseif($po->status === 'in_progress')
                        <button wire:click="updateStatus({{ $po->id }}, 'qc')" class="w-full py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-bold transition-colors">
                            XONG - CHUYỂN QC
                        </button>
                    @elseif($po->status === 'qc')
                        <div class="grid grid-cols-2 gap-2">
                            <button wire:click="updateStatus({{ $po->id }}, 'in_progress')" class="py-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg text-xs font-bold transition-colors">
                                FAIL QC
                            </button>
                            <button wire:click="updateStatus({{ $po->id }}, 'completed')" class="py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-bold transition-colors">
                                PASS QC - NHẬP KHO
                            </button>
                        </div>
                    @else
                        <div class="text-center py-2 text-green-600 font-bold text-sm">
                            <i class="fa-solid fa-circle-check mr-2"></i> ĐÃ HOÀN THÀNH
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-xl border border-dashed border-gray-300 text-center">
                <i class="fa-solid fa-industry text-4xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-400 font-medium">Không có lệnh sản xuất nào ở trạng thái này.</p>
            </div>
        @endforelse
    </div>
</div>
