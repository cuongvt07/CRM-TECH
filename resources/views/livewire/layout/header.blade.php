<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6 border-b border-gray-200">
    <!-- Global Search -->
    <div class="flex-1 max-w-lg relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary sm:text-sm transition duration-150 ease-in-out" placeholder="Global Search (Ctrl+K)">
    </div>

    <!-- Actions -->
    <div class="ml-4 flex items-center md:ml-6 space-x-4">
        <!-- Notification -->
        <button class="p-1 text-gray-400 hover:text-gray-500 relative focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-error ring-2 ring-white"></span>
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
                    <a href="#" class="group flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-primary transition-colors">
                        <i class="fa-solid fa-gear mr-3 text-gray-400 group-hover:text-primary"></i>
                        Cài đặt
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
</header>
