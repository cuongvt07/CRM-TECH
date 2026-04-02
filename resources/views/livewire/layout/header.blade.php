<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 border-b border-gray-200 z-[100]">
    <style>
        @keyframes vibrate {
            0% { transform: rotate(0deg); }
            25% { transform: rotate(10deg); }
            50% { transform: rotate(0deg); }
            75% { transform: rotate(-10deg); }
            100% { transform: rotate(0deg); }
        }
        .animate-vibrate { animation: vibrate 0.15s linear infinite; }
    </style>

    <!-- Global Search -->
    <div class="flex-1 max-w-lg relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out" placeholder="Tìm kiếm nhanh... (Ctrl+K)">
    </div>

    <!-- Actions -->
    <div class="ml-4 flex items-center md:ml-6 space-x-4">
        <!-- Notification Bell -->
        <button wire:click="toggleNotificationSlide" 
                class="p-2 {{ $totalPending > 0 ? 'text-red-600 bg-red-50' : 'text-gray-400 hover:bg-gray-50' }} rounded-full relative focus:outline-none transition-all">
            <i class="fa-solid fa-bell text-xl {{ $totalPending > 0 ? 'animate-vibrate' : '' }}"></i>
            @if($totalPending > 0)
                <span class="absolute top-1.5 right-1.5 block h-4 w-4 rounded-full bg-red-600 text-white text-[9px] font-black flex items-center justify-center border-2 border-white">
                    {{ $totalPending }}
                </span>
            @endif
        </button>

        <!-- Profile dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
            <div>
                <button @click="open = !open" class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:shadow-solid hover:bg-gray-50 p-1" id="user-menu" aria-expanded="true" aria-haspopup="true">
                    <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin&background=1677FF&color=fff" alt="">
                    <span class="ml-2 font-medium text-gray-700 hidden sm:block">Admin</span>
                    <i class="fa-solid fa-chevron-down ml-2 text-gray-400 text-xs hidden sm:block"></i>
                </button>
            </div>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-100" 
                 x-transition:enter-start="transform opacity-0 scale-95" 
                 x-transition:enter-end="transform opacity-100 scale-100" 
                 x-transition:leave="transition ease-in duration-75" 
                 x-transition:leave-start="transform opacity-100 scale-100" 
                 x-transition:leave-end="transform opacity-0 scale-95" 
                 style="display: none;"
                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 divide-y divide-gray-100">
                
                <div class="px-4 py-3">
                    <p class="text-sm font-medium text-gray-900 truncate">admin@erp.com</p>
                    <p class="text-xs text-gray-500 mt-1">Quản trị viên</p>
                </div>
                <div class="py-1">
                    <a wire:navigate href="{{ route('profile') }}" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fa-solid fa-user-gear mr-3 text-gray-400 group-hover:text-primary"></i>
                        Hồ sơ cá nhân
                    </a>
                </div>
                <div class="py-1">
                    <button wire:click="logout" class="group flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket mr-3 text-red-500 group-hover:text-red-700"></i>
                        Đăng xuất
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- GLOBAL NOTIFICATION SLIDE-OVER (RESIZABLE) --}}
    <div x-data="{ 
            open: @entangle('showNotificationSlide'), 
            width: 450, 
            isResizing: false 
         }" 
         @mousemove.window="if (isResizing) { width = Math.max(300, Math.min(window.innerWidth - 100, window.innerWidth - $event.clientX)) }"
         @mouseup.window="isResizing = false"
         x-show="open" 
         class="fixed inset-0 overflow-hidden z-[999]" 
         style="display: none;"
         x-cloak>
        <div class="absolute inset-0 overflow-hidden">
            {{-- Dark Overlay --}}
            <div x-show="open" 
                 x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in-out duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="absolute inset-0 bg-gray-500/75 transition-opacity" 
                 @click="open = false"></div>

            <div class="fixed inset-y-0 right-0 flex">
                {{-- Slide-over Panel --}}
                <div x-show="open" 
                     :style="'width: ' + width + 'px'"
                     x-transition:enter="transform transition ease-in-out duration-500" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-500" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" 
                     class="h-full flex relative transition-all">
                    
                    {{-- Resize Handle --}}
                    <div @mousedown="isResizing = true" 
                         class="absolute inset-y-0 left-0 w-1.5 cursor-col-resize hover:bg-red-500/30 group transition-all z-10 flex items-center justify-center">
                        <div class="w-[2px] h-10 bg-gray-200 group-hover:bg-red-500 rounded-full"></div>
                    </div>

                    <div class="h-full flex flex-col bg-white shadow-2xl flex-1 min-w-0">
                        {{-- Header --}}
                        <div class="px-6 py-4 bg-red-600 shadow-lg">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-black text-white flex items-center uppercase tracking-tighter">
                                    <i class="fa-solid fa-bell-concierge mr-3 animate-bounce"></i>
                                    Trung tâm Tác vụ
                                </h2>
                                <button @click="open = false" class="bg-red-700 p-1.5 rounded-md text-red-200 hover:text-white transition-colors">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Body --}}
                        <div class="flex-1 flex flex-col min-h-0 bg-gray-50/50">
                            {{-- Tab Switcher --}}
                            <div class="flex border-b border-gray-200 bg-white">
                                <button wire:click="setActiveTab('pending')" 
                                        class="flex-1 py-3 text-xs font-black uppercase tracking-widest transition-all {{ $activeTab === 'pending' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                                    Đang chờ ({{ $totalPending }})
                                </button>
                                <button wire:click="setActiveTab('history')" 
                                        class="flex-1 py-3 text-xs font-black uppercase tracking-widest transition-all {{ $activeTab === 'history' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                                    Lịch sử (60 ngày)
                                </button>
                            </div>

                            <div class="flex-1 overflow-y-auto px-6 py-6 space-y-6">
                                @if($activeTab === 'pending')
                                    {{-- Group: Sales --}}
                                    @if($pendingOrders->count() > 0)
                                        <div class="space-y-4">
                                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-l-4 border-red-500 pl-3">Kinh doanh (Orders)</h3>
                                            @foreach($pendingOrders as $order)
                                            <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 ring-1 ring-black/5">
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">#{{ $order->id }}</span>
                                                    <span class="text-[10px] text-gray-400 italic">{{ $order->created_at->format('H:i') }}</span>
                                                </div>
                                                <h4 class="font-bold text-gray-800 text-sm mb-3">KH: {{ $order->customer_name }}</h4>
                                                
                                                <textarea wire:model="feedbackNotes.Order-{{ $order->id }}" placeholder="Ghi chú phản hồi..." class="w-full text-xs rounded-xl border-gray-200 mb-3 h-12 bg-gray-50 focus:bg-white"></textarea>

                                                <div class="grid grid-cols-2 gap-2">
                                                    <button wire:click="confirmWarehouseStock({{ $order->id }}, 'sufficient')" class="py-1.5 bg-green-600 text-white rounded-lg text-[10px] font-bold uppercase hover:bg-green-700">Đủ hàng</button>
                                                    <button wire:click="confirmWarehouseStock({{ $order->id }}, 'insufficient')" class="py-1.5 bg-red-600 text-white rounded-lg text-[10px] font-bold uppercase hover:bg-red-700">Thiếu hàng</button>
                                                    <button wire:click="confirmWarehouseStock({{ $order->id }}, 'pending_production')" class="py-1.5 bg-orange-500 text-white rounded-lg text-[10px] font-bold uppercase hover:bg-orange-600">Sản xuất</button>
                                                    <button wire:click="confirmWarehouseStock({{ $order->id }}, 'delivering')" class="py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-bold uppercase hover:bg-blue-700">Đang giao</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Group: Production --}}
                                    @if($pendingProductionOrders->count() > 0)
                                        <div class="space-y-4 pt-4">
                                            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-l-4 border-blue-500 pl-3">Sản xuất (Materials)</h3>
                                            @foreach($pendingProductionOrders as $po)
                                            <div class="bg-white rounded-2xl p-4 shadow-sm border border-blue-100 ring-1 ring-blue-500/5">
                                                <div class="flex justify-between mb-2">
                                                    <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-0.5 rounded-full">#{{ $po->id }}</span>
                                                    <span class="text-[10px] text-gray-400 italic">{{ $po->created_at->diffForHumans() }}</span>
                                                </div>
                                                <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $po->product->name }}</h4>
                                                <p class="text-[11px] text-gray-500 mb-3">Yêu cầu chuẩn bị vật tư</p>
                                                
                                                <textarea wire:model="feedbackNotes.Production-{{ $po->id }}" placeholder="Ghi chú phản hồi..." class="w-full text-xs rounded-xl border-gray-200 mb-3 h-12 bg-blue-50/5 focus:bg-white"></textarea>

                                                <div class="grid grid-cols-2 gap-2">
                                                    <button wire:click="confirmProductionRequest({{ $po->id }}, 'sufficient')" class="py-1.5 bg-green-600 text-white rounded-lg text-[10px] font-bold uppercase">Đủ VT</button>
                                                    <button wire:click="confirmProductionRequest({{ $po->id }}, 'insufficient')" class="py-1.5 bg-red-600 text-white rounded-lg text-[10px] font-bold uppercase">Thiếu VT</button>
                                                    <button wire:click="confirmProductionRequest({{ $po->id }}, 'pending_production')" class="py-1.5 bg-orange-500 text-white rounded-lg text-[10px] font-bold uppercase">Chờ MH</button>
                                                    <button wire:click="confirmProductionRequest({{ $po->id }}, 'delivering')" class="py-1.5 bg-blue-600 text-white rounded-lg text-[10px] font-bold uppercase">Đang soạn</button>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if($totalPending == 0)
                                        <div class="text-center py-20 text-gray-400 opacity-50">
                                            <i class="fa-solid fa-mug-hot text-5xl mb-4"></i>
                                            <p class="text-sm">Mọi thứ đã được xử lý xong!</p>
                                        </div>
                                    @endif

                                @else
                                    {{-- History --}}
                                    <div class="space-y-6">
                                        @foreach($historyRequests as $hist)
                                        <div class="relative pl-6 border-l border-gray-200 pb-6 last:pb-0">
                                            <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full border-2 border-white 
                                                {{ $hist->warehouse_status === 'sufficient' ? 'bg-green-500' : '' }}
                                                {{ $hist->warehouse_status === 'insufficient' ? 'bg-red-500' : '' }}
                                                {{ $hist->warehouse_status === 'pending_production' ? 'bg-orange-500' : '' }}
                                                {{ $hist->warehouse_status === 'delivering' ? 'bg-blue-500' : '' }}
                                            "></div>
                                            <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100">
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-[10px] font-black uppercase text-gray-900">#{{ $hist->id }} - {{ $hist->request_type === 'Order' ? 'Sales' : 'Production' }}</span>
                                                    <span class="text-[9px] text-gray-400">{{ $hist->warehouse_confirmed_at->format('d/m H:i') }}</span>
                                                </div>
                                                <p class="text-[11px] font-bold text-gray-700 truncate mb-1">
                                                    {{ $hist->request_type === 'Order' ? 'KH: ' . $hist->customer_name : 'SP: ' . $hist->product->name }}
                                                </p>
                                                @if($hist->warehouse_note)
                                                    <div class="text-[10px] text-gray-500 italic bg-gray-50 p-1.5 rounded mt-1 border border-gray-100">"{{ $hist->warehouse_note }}"</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
