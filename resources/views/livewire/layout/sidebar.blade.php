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
        <div x-data="{ open: {{ request()->routeIs('production.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="{{ request()->routeIs('production.*') ? 'bg-amber-50 text-amber-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} w-full flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Quản trị sản xuất' : ''">
                <i class="fa-solid fa-industry text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 flex-1 text-left truncate uppercase font-bold text-sm tracking-widest">Quản trị sản xuất</span>
                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-xs text-gray-400 transition-transform"></i>
            </button>
            <div x-show="open && sidebarOpen" x-transition class="pl-[3.25rem] pr-3 py-1 space-y-1 bg-blue-50/30 rounded-b-lg">
                <a wire:navigate href="{{ route('production.bom') }}" class="{{ request()->routeIs('production.bom') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Quản lý BOM</span>
                </a>
                <a wire:navigate href="{{ route('production.pp') }}" class="{{ request()->routeIs('production.pp') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Quản lý năng lực sản xuất tổng thể (PP)</span>
                </a>
                <a wire:navigate href="{{ route('production.dms') }}" class="{{ request()->routeIs('production.dms') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Quản lý nhu cầu sản xuất DMS</span>
                </a>
                <a wire:navigate href="{{ route('production.mrp') }}" class="{{ request()->routeIs('production.mrp') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Hoạch định nhu cầu nguyên vật liệu (MRP)</span>
                </a>
                <a wire:navigate href="{{ route('production.planner') }}" class="{{ request()->routeIs('production.planner') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-amber-600 hover:text-amber-700 bg-amber-50/50' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors border-l-2 border-amber-400">
                    <i class="fa-solid fa-caret-right text-amber-500 text-sm mr-4 ml-0.5 shrink-0"></i>
                    <span class="truncate">Lập kế hoạch sản xuất (MPS)</span>
                </a>
                <a wire:navigate href="{{ route('production.routing') }}" class="{{ request()->routeIs('production.routing') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Quản lý quy trình sản xuất (Routing)</span>
                </a>
                <a wire:navigate href="{{ route('production.stats') }}" class="{{ request()->routeIs('production.stats') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Thống kê công đoạn sản xuất</span>
                </a>
                <a wire:navigate href="{{ route('production.analytics') }}" class="{{ request()->routeIs('production.analytics') ? 'text-amber-700 font-bold bg-amber-100/50' : 'text-slate-700 hover:text-amber-600' }} flex flex-row items-center py-2.5 px-3 rounded-md text-sm transition-colors">
                    <i class="fa-solid fa-gear-complex text-slate-800 text-sm mr-3 shrink-0"></i>
                    <span class="truncate">Báo cáo quản trị sản xuất</span>
                </a>
            </div>
        </div>
        
        <!-- QA/QC Module -->
        <div x-data="{ open: {{ request()->routeIs('qaqc.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="{{ request()->routeIs('qaqc.*') ? 'bg-emerald-50 text-emerald-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} w-full flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'QA/QC' : ''">
                <i class="fa-solid fa-shield-check text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 flex-1 text-left truncate uppercase font-bold text-sm tracking-widest text-emerald-700">QA/QC</span>
                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-xs text-gray-400 transition-transform"></i>
            </button>
            <div x-show="open && sidebarOpen" x-transition class="pl-[3.25rem] pr-3 py-1 space-y-1">
                <a wire:navigate href="{{ route('qaqc.bom') }}" class="{{ request()->routeIs('qaqc.bom') ? 'text-emerald-600 font-medium' : 'text-gray-500 hover:text-emerald-600' }} block py-2 text-sm transition-colors">
                    Duyệt BOM/NVL
                </a>
                <a wire:navigate href="{{ route('qaqc.plan') }}" class="{{ request()->routeIs('qaqc.plan') ? 'text-emerald-600 font-medium' : 'text-gray-500 hover:text-emerald-600' }} block py-2 text-sm transition-colors">
                    Check-list SX
                </a>
                <a wire:navigate href="{{ route('qaqc.reports') }}" class="{{ request()->routeIs('qaqc.reports') ? 'text-emerald-600 font-medium' : 'text-gray-500 hover:text-emerald-600' }} block py-2 text-sm transition-colors">
                    Báo cáo QA/QC
                </a>
            </div>
        </div>

        <!-- WAREHOUSE MODULE -->
        <div x-data="{ open: {{ request()->routeIs('warehouse.*') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open; if(!sidebarOpen) sidebarOpen = true" class="{{ request()->routeIs('warehouse.*') ? 'bg-orange-50 text-orange-600 font-semibold' : 'text-gray-600 hover:bg-gray-50' }} w-full flex items-center p-3 rounded-lg transition-colors group" :title="!sidebarOpen ? 'Kho' : ''">
                <i class="fa-solid fa-warehouse-full text-xl w-6 h-6 flex items-center justify-center shrink-0"></i>
                <span x-show="sidebarOpen" class="ml-3 flex-1 text-left truncate uppercase font-bold text-sm tracking-widest text-orange-700">MODULE KHO</span>
                <i x-show="sidebarOpen" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" class="fa-solid text-xs text-gray-400 transition-transform"></i>
            </button>
            <div x-show="open && sidebarOpen" x-transition class="pl-8 pr-3 py-1 space-y-3">
                
                <!-- Kho Thành Phẩm -->
                <div x-data="{ subOpen: false }" class="space-y-1">
                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between text-xs font-black text-slate-500 hover:text-orange-600 uppercase tracking-tight">
                        <span><i class="fa-solid fa-boxes-packing mr-2"></i>Kho thành phẩm</span>
                        <i :class="subOpen ? 'fa-caret-up' : 'fa-caret-down'" class="fa-solid text-[10px]"></i>
                    </button>
                    <div x-show="subOpen" class="pl-4 border-l border-slate-100 mt-2 space-y-2">
                        <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'import', 'warehouse_code' => 'FINISHED_GOODS']) }}" class="block text-[11px] font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors">Nhập kho</a>
                        <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'export', 'warehouse_code' => 'FINISHED_GOODS']) }}" class="block text-[11px] font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors">Xuất kho</a>
                        <a wire:navigate href="{{ route('warehouse.index', ['filterWarehouse' => 3]) }}" class="block text-[11px] font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors">Tồn kho</a>
                    </div>
                </div>

                <!-- Kho Nguyên Vật Liệu -->
                <div x-data="{ subOpen: false }" class="space-y-1">
                    <button @click="subOpen = !subOpen" class="w-full flex items-center justify-between text-xs font-black text-slate-500 hover:text-orange-600 uppercase tracking-tight">
                        <span><i class="fa-solid fa-conveyor-belt mr-2"></i>Kho nguyên vật liệu</span>
                        <i :class="subOpen ? 'fa-caret-up' : 'fa-caret-down'" class="fa-solid text-[10px]"></i>
                    </button>
                    <div x-show="subOpen" class="pl-4 border-l border-slate-100 mt-2 space-y-2">
                        <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'import', 'warehouse_code' => 'RAW_MAT']) }}" class="block text-[11px] font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors">Nhập kho</a>
                        <a wire:navigate href="{{ route('warehouse.transaction.create', ['type' => 'export', 'warehouse_code' => 'RAW_MAT']) }}" class="block text-[11px] font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors">Xuất kho</a>
                        <a wire:navigate href="{{ route('warehouse.index', ['filterWarehouse' => 1]) }}" class="block text-[11px] font-bold text-slate-400 hover:text-orange-500 uppercase tracking-widest transition-colors">Tồn kho</a>
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-50 space-y-2">
                    <a wire:navigate href="{{ route('warehouse.report') }}" class="flex items-center text-xs font-black text-slate-500 hover:text-orange-600 uppercase tracking-widest transition-colors">
                        <i class="fa-solid fa-chart-pie-simple mr-3 text-sm"></i>Báo cáo
                    </a>
                    <a wire:navigate href="{{ route('warehouse.history') }}" class="flex items-center text-xs font-black text-slate-500 hover:text-orange-600 uppercase tracking-widest transition-colors">
                        <i class="fa-solid fa-clock-rotate-left mr-3 text-sm"></i>Lịch sử
                    </a>
                    <a wire:navigate href="{{ route('warehouse.settings') }}" class="flex items-center text-xs font-black text-slate-500 hover:text-orange-600 uppercase tracking-widest transition-colors">
                        <i class="fa-solid fa-gear-complex mr-3 text-sm"></i>Cấu hình
                    </a>
                </div>
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
