<aside 
    x-data="{ sidebarOpen: true }"
    :class="sidebarOpen ? 'w-64' : 'w-20'" 
    class="bg-white shadow-md flex flex-col h-full border-r border-gray-200 transition-all duration-300 shrink-0"
>
    <div class="h-16 flex items-center border-b border-gray-200 px-4" :class="sidebarOpen ? 'justify-between' : 'justify-center'">
        <h1 x-show="sidebarOpen" class="text-xl font-bold text-primary truncate">ERP</h1>
        <!-- Toggle button -->
        <button @click="sidebarOpen = !sidebarOpen" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-500 focus:outline-none transition-colors border border-transparent hover:border-gray-200 shrink-0 flex items-center justify-center" :title="sidebarOpen ? 'Thu gọn' : 'Mở rộng'">
            <i :class="sidebarOpen ? 'fa-solid fa-angles-left' : 'fa-solid fa-angles-right'" class="text-xl w-6 h-6 flex items-center justify-center"></i>
        </button>
    </div>
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto overflow-x-hidden">
        <a wire:navigate href="/" class="{{ request()->is('/') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Dashboard' : ''">
            <i class="fa-solid fa-gauge text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Dashboard</span>
        </a>
        <a wire:navigate href="#" class="text-gray-600 hover:bg-gray-50 flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Thông báo' : ''">
            <i class="fa-solid fa-bell text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Thông báo</span>
        </a>
        <a wire:navigate href="#" class="text-gray-600 hover:bg-gray-50 flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Sản phẩm' : ''">
            <i class="fa-solid fa-box-open text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Sản phẩm</span>
        </a>
        <a wire:navigate href="#" class="text-gray-600 hover:bg-gray-50 flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Bán hàng' : ''">
            <i class="fa-solid fa-cart-shopping text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Bán hàng</span>
        </a>
        <a wire:navigate href="#" class="text-gray-600 hover:bg-gray-50 flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Sản xuất' : ''">
            <i class="fa-solid fa-industry text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Sản xuất</span>
        </a>
        <a wire:navigate href="#" class="text-gray-600 hover:bg-gray-50 flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Kho' : ''">
            <i class="fa-solid fa-warehouse text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Kho</span>
        </a>
    </nav>
</aside>
