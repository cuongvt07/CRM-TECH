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
        <div class="relative">
            <button class="max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:shadow-solid" id="user-menu" aria-label="User menu" aria-haspopup="true">
                <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name=Admin&background=1677FF&color=fff" alt="">
                <span class="ml-2 font-medium text-gray-700">Admin</span>
            </button>
        </div>
    </div>
</header>
