<aside 
    x-data="{ sidebarOpen: true }"
    class="w-64 bg-white shadow-md flex flex-col h-full border-r border-gray-200 transition-all duration-300 shrink-0"
>
    <!-- Logo & Toggle -->
    <div class="h-16 flex items-center border-b border-gray-200 px-4 justify-between">
        <h1 class="text-xl font-bold text-primary truncate">ERP SYSTEM</h1>
        <button @click="sidebarOpen = !sidebarOpen" class="p-1.5 rounded-md hover:bg-gray-100 text-gray-500 transition-colors border border-transparent hover:border-gray-200">
            <i :class="sidebarOpen ? 'fa-solid fa-angles-left' : 'fa-solid fa-angles-right'" class="text-xl"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 space-y-2 overflow-y-auto overflow-x-hidden">
        <a wire:navigate href="/" class="flex items-center p-3 rounded-lg bg-blue-50 text-primary font-semibold transition-colors group">
            <i class="fa-solid fa-gauge text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span class="ml-3 truncate">Bảng điều khiển</span>
        </a>
        <a wire:navigate href="{{ route('notifications.index') }}" class="{{ request()->routeIs('notifications.index') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Thông báo' : ''">
            <i class="fa-solid fa-bell text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Thông báo</span>
        </a>
        <div x-data="{ open: {{ request()->routeIs('products.*') || request()->routeIs('categories.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="{{ request()->routeIs('products.*') || request()->routeIs('categories.*') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} w-full flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Sản phẩm' : ''">
                <i class="fa-solid fa-box-open text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 flex-1 text-left truncate">Sản phẩm</span>
                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-xs text-gray-400 transition-transform"></i>
            </button>
            <div x-show="open && sidebarOpen" x-transition class="pl-[3.25rem] pr-3 py-1 space-y-1">
                <a wire:navigate href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Tên sản phẩm
                </a>
                <a wire:navigate href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary block py-2 text-sm transition-colors opacity-75 italic">
                    BOM sản phẩm
                </a>
                <a wire:navigate href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Danh mục sản phẩm
                </a>
            </div>
        </div>
        <div x-data="{ open: {{ request()->routeIs('orders.*') || request()->routeIs('customers.*') || request()->routeIs('sales.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="{{ request()->routeIs('orders.*') || request()->routeIs('customers.*') || request()->routeIs('sales.*') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} w-full flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Bán hàng' : ''">
                <i class="fa-solid fa-cart-shopping text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 flex-1 text-left truncate">BÁN HÀNG</span>
                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-xs text-gray-400 transition-transform"></i>
            </button>
            <div x-show="open && sidebarOpen" x-transition class="pl-[3.25rem] pr-3 py-1 space-y-1">
                <a wire:navigate href="{{ route('orders.index') }}" class="{{ request()->routeIs('orders.index') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Danh sách đơn hàng
                </a>
                <a wire:navigate href="{{ route('customers.index') }}" class="{{ request()->routeIs('customers.index') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Danh sách khách hàng
                </a>
                <a wire:navigate href="{{ route('sales.report') }}" class="{{ request()->routeIs('sales.report') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Báo cáo bán hàng
                </a>
            </div>
        </div>
        <a wire:navigate href="{{ route('production.index') }}" class="{{ request()->routeIs('production.*') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Sản xuất' : ''">
            <i class="fa-solid fa-industry text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Sản xuất</span>
        </a>
        <div x-data="{ open: {{ request()->routeIs('warehouse.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="{{ request()->routeIs('warehouse.*') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} w-full flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Kho' : ''">
                <i class="fa-solid fa-warehouse text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 flex-1 text-left truncate">KHO</span>
                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-xs text-gray-400 transition-transform"></i>
            </button>
            <div x-show="open && sidebarOpen" x-transition class="pl-[3.25rem] pr-3 py-1 space-y-1">
                <a wire:navigate href="{{ route('warehouse.index') }}" class="{{ request()->routeIs('warehouse.index') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Quản lý kho
                </a>
                <a wire:navigate href="{{ route('warehouse.report') }}" class="{{ request()->routeIs('warehouse.report') ? 'text-primary font-medium' : 'text-gray-500 hover:text-primary' }} block py-2 text-sm transition-colors">
                    Báo cáo kho
                </a>
            </div>
        </div>
        <a wire:navigate href="{{ route('employees.index') }}" class="{{ request()->is('employees*') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Nhân sự' : ''">
            <i class="fa-solid fa-users text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Nhân sự</span>
        </a>
        <a wire:navigate href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.*') && !request()->routeIs('departments.my') ? 'bg-blue-50 text-primary font-semibold' : 'text-gray-600 hover:bg-gray-50' }} flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Phòng ban' : ''">
            <i class="fa-solid fa-sitemap text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
            <span x-show="sidebarOpen" class="ml-3 truncate">Phòng ban</span>
        </a>
    </nav>
</aside>
