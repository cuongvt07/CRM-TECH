<div class="p-6 bg-slate-50 min-h-screen">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Kế hoạch làm việc QA/QC</h1>
        <p class="text-slate-500 text-sm">Giám sát và kiểm soát chất lượng lệnh sản xuất đang vận hành</p>
    </div>

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

    <style>
        .customize-scrollbar::-webkit-scrollbar { width: 4px; }
        .customize-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .customize-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</div>
