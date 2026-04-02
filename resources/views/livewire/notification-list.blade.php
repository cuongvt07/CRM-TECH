<div class="space-y-6">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Thông báo</h2>
            <p class="text-sm text-gray-500">Cập nhật mới nhất về đơn hàng và kho.</p>
        </div>
        @if($notifications->where('is_read', false)->count() > 0)
            <button wire:click="markAllAsRead" class="text-xs text-primary hover:underline font-medium">Đánh dấu tất cả đã đọc</button>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-50 overflow-hidden">
        @forelse($notifications as $notification)
            <div class="p-4 flex items-start space-x-4 {{ $notification->is_read ? 'bg-white opacity-70' : 'bg-blue-50/30' }} transition-colors hover:bg-gray-50 group">
                <div class="flex-shrink-0 mt-1">
                    @if($notification->type === 'STOCK_WARNING')
                        <div class="p-2 bg-red-100 text-red-600 rounded-full">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                    @elseif($notification->type === 'ORDER_CREATED')
                        <div class="p-2 bg-blue-100 text-blue-600 rounded-full">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                    @else
                        <div class="p-2 bg-gray-100 text-gray-600 rounded-full">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                    @endif
                </div>
                
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold {{ $notification->is_read ? 'text-gray-700' : 'text-blue-900 font-extrabold' }}">
                            {{ $notification->title }}
                        </h3>
                        <span class="text-[10px] text-gray-400 font-medium">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">
                        {{ $notification->message }}
                    </p>
                    
                    @if(!$notification->is_read)
                        <button wire:click="markAsRead({{ $notification->id }})" class="mt-2 text-[10px] font-bold text-primary hover:text-blue-700 uppercase tracking-wider">Đã đọc</button>
                    @endif
                </div>
                
                @if($notification->reference_type === 'Order')
                    <div class="shrink-0 flex items-center h-full">
                        <a wire:navigate href="{{ route('orders.index') }}" class="p-2 text-gray-400 hover:text-primary transition-colors">
                            <i class="fa-solid fa-chevron-right text-xs"></i>
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="p-12 text-center">
                <div class="inline-flex items-center justify-center p-6 bg-gray-50 rounded-full mb-4">
                    <i class="fa-solid fa-bell-slash text-4xl text-gray-200"></i>
                </div>
                <p class="text-gray-500 font-medium">Bạn chưa có thông báo nào</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $notifications->links() }}
    </div>
</div>
