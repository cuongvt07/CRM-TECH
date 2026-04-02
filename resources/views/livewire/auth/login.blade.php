<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-xl shadow-lg relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-2 bg-primary"></div>
        
        <div>
            <h2 class="mt-4 text-center text-3xl font-extrabold text-gray-900">
                Đăng nhập ERP
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Nhập số điện thoại và mật khẩu của bạn để tiếp tục
            </p>
        </div>
        
        <form class="mt-8 space-y-6" wire:submit="login">
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="rounded-md shadow-sm space-y-4">
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-phone text-gray-400"></i>
                        </div>
                        <input wire:model="phone" id="phone" type="text" autocomplete="tel" required class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="0901234567">
                    </div>
                    @error('phone') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fa-solid fa-lock text-gray-400"></i>
                        </div>
                        <input wire:model="password" id="password" type="password" autocomplete="current-password" required class="focus:ring-primary focus:border-primary block w-full pl-10 sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="••••••••">
                    </div>
                    @error('password') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="remember" id="remember-me" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ghi nhớ đăng nhập
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-primary hover:text-blue-500 transition-colors">
                        Quên mật khẩu?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fa-solid fa-arrow-right-to-bracket text-blue-400 group-hover:text-blue-300"></i>
                    </span>
                    <span wire:loading.remove wire:target="login">Đăng nhập</span>
                    <span wire:loading wire:target="login">Đang xử lý...</span>
                </button>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500">Tài khoản demo: 0901234567 / password</p>
            </div>
        </form>
    </div>
</div>
