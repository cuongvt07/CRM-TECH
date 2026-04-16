<div class="flex flex-col h-full bg-slate-50 overflow-hidden">
    <!-- Header: Ultra Compact -->
    <div class="flex justify-between items-center px-3 py-1.5 bg-white border-b border-slate-200 z-10 shadow-sm">
        <h2 class="text-[11px] font-black text-slate-800 flex items-center uppercase tracking-tighter">
            <i class="fa-solid fa-list-check mr-2 text-primary text-[10px]"></i> ĐIỀU PHỐI SẢN XUẤT
        </h2>
        <div class="flex items-center space-x-2">
            <a href="{{ route('production.planner') }}" class="px-2 py-0.5 bg-primary text-white rounded text-[9px] font-black hover:bg-primary-dark transition-all uppercase shadow-sm">
                <i class="fa-solid fa-plus-circle mr-1"></i> Lệnh mới
            </a>
        </div>
    </div>

    <!-- KANBAN BOARD: Extreme Density -->
    <div class="flex-1 overflow-x-auto overflow-y-hidden">
        <div class="flex h-full p-2 space-x-2 bg-slate-50/50" style="width: fit-content; min-width: 100%;">
            @php
                $statuses = [
                    'pending' => ['name' => 'CHỜ XỬ LÝ', 'color' => 'slate', 'bg' => 'bg-slate-100', 'border' => 'border-slate-300'],
                    'in_progress' => ['name' => 'ĐANG LÀM', 'color' => 'orange', 'bg' => 'bg-orange-100', 'border' => 'border-orange-300'],
                    'qc' => ['name' => 'K.TRA QC', 'color' => 'purple', 'bg' => 'bg-purple-100', 'border' => 'border-purple-300'],
                    'completed' => ['name' => 'HOÀN TẤT', 'color' => 'green', 'bg' => 'bg-green-100', 'border' => 'border-green-300'],
                ];
            @endphp

            @foreach($statuses as $status => $meta)
                <div class="flex flex-col w-[215px] {{ $meta['bg'] }}/40 rounded border border-{{ $meta['color'] }}-200 h-full shadow-inner">
                    <!-- Column Header -->
                    <div class="flex justify-between items-center px-2 py-1 bg-white/80 border-b border-{{ $meta['color'] }}-200/50 mb-1">
                        <h3 class="font-black text-{{ $meta['color'] }}-900 uppercase tracking-tighter text-[9px]">{{ $meta['name'] }}</h3>
                        <span class="bg-{{ $meta['color'] }}-500 text-white text-[8px] font-black px-1 rounded shadow-sm">
                            {{ count($ordersByStatus[$status] ?? []) }}
                        </span>
                    </div>

                    <!-- Cards Container -->
                    <div 
                        id="list-{{ $status }}" 
                        data-status="{{ $status }}"
                        class="kanban-list flex-1 space-y-1.5 overflow-y-auto px-1 customize-scrollbar pb-10"
                    >
                        @foreach($ordersByStatus[$status] ?? [] as $po)
                            <div 
                                data-id="{{ $po->id }}"
                                class="kanban-card bg-white rounded shadow-sm p-2 border border-slate-200 hover:border-primary transition-all cursor-move group relative"
                            >
                                <div class="flex justify-between items-start mb-0.5">
                                    <span class="text-[7px] font-black text-slate-300 uppercase italic">#{{ str_pad($po->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    @if($po->order_id)
                                        <span class="text-[7px] font-black text-blue-800 bg-blue-50 px-1 rounded border border-blue-100 uppercase">
                                            Đ{{ $po->order_id }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Product Name: Multi-line, No clipping -->
                                <h4 class="font-bold text-slate-800 text-[10px] mb-1.5 leading-tight break-words overflow-visible">
                                    {{ $po->product?->name }}
                                </h4>

                                <!-- Material Logic -->
                                @if(in_array($status, ['pending', 'in_progress']))
                                    @php $matStatus = $po->getMaterialStatus(); @endphp
                                    <div class="mb-1.5">
                                        @if($matStatus['status'] === 'sufficient')
                                            <span class="text-[7px] font-black text-green-700 bg-green-50 px-1 rounded border border-green-200 uppercase tracking-tighter">
                                                <i class="fa-solid fa-check text-[6px]"></i> ĐỦ VẬT TƯ
                                            </span>
                                        @else
                                            <span class="text-[7px] font-black text-red-700 bg-red-50 px-1 rounded border border-red-200 uppercase tracking-tighter animate-pulse">
                                                <i class="fa-solid fa-warning text-[6px]"></i> THIẾU NVL
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Details Grid -->
                                <div class="grid grid-cols-2 gap-1 mb-1.5">
                                    <div class="bg-slate-50 rounded px-1 py-0.5 border border-slate-100 text-center">
                                        <div class="text-[6px] text-slate-400 font-bold uppercase leading-none">SL</div>
                                        <div class="text-[10px] font-black text-slate-700 leading-none mt-0.5">@nfmt($po->quantity)</div>
                                    </div>
                                    <div class="bg-slate-50 rounded px-1 py-0.5 border border-slate-100 text-center">
                                        <div class="text-[6px] text-slate-400 font-bold uppercase leading-none">HẠN</div>
                                        <div class="text-[9px] font-black text-{{ $po->end_date && $po->end_date->isPast() ? 'red' : 'slate' }}-600 leading-none mt-0.5">
                                            {{ $po->end_date ? $po->end_date->format('d/m') : '--/--' }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-1 border-t border-slate-50">
                                    <div class="flex items-center space-x-1 min-w-0">
                                        <div class="w-3.5 h-3.5 rounded-full bg-slate-100 text-slate-500 flex-shrink-0 flex items-center justify-center text-[7px] font-black border border-slate-200 shadow-sm">
                                            {{ substr($po->assignee?->name ?? '?', 0, 1) }}
                                        </div>
                                        <span class="text-[8px] font-bold text-slate-500 break-words leading-none">{{ $po->assignee?->name ?? 'N/A' }}</span>
                                    </div>

                                    @if($status === 'qc')
                                        <div class="flex space-x-0.5">
                                            <button wire:click="updateStatus({{ $po->id }}, 'in_progress')" class="w-5 h-5 flex items-center justify-center bg-red-50 text-red-600 rounded border border-red-200 hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <i class="fa-solid fa-reply text-[7px]"></i>
                                            </button>
                                            <button wire:click="updateStatus({{ $po->id }}, 'completed')" class="w-5 h-5 flex items-center justify-center bg-green-50 text-green-700 rounded border border-green-200 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                                                <i class="fa-solid fa-check text-[7px]"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        .customize-scrollbar::-webkit-scrollbar { width: 3px; height: 3px; }
        .customize-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .customize-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        .kanban-list { scroll-behavior: smooth; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            const lists = document.querySelectorAll('.kanban-list');
            lists.forEach(el => {
                new Sortable(el, {
                    group: 'production',
                    animation: 200,
                    ghostClass: 'bg-primary/5',
                    onEnd: function (evt) {
                        const id = evt.item.getAttribute('data-id');
                        const newStatus = evt.to.getAttribute('data-status');
                        const oldStatus = evt.from.getAttribute('data-status');
                        if (newStatus !== oldStatus) {
                            @this.updateStatus(id, newStatus);
                        }
                    }
                });
            });
        });
    </script>
</div>
