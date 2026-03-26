<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Profile Header -->
            <div class="relative h-32 bg-primary"></div>
            
            <div class="px-8 pb-8">
                <!-- Avatar & Basic Info -->
                <div class="relative flex justify-between items-end -mt-12 mb-6">
                    <div class="flex items-end space-x-5">
                        <div class="relative">
                            <img class="h-24 w-24 rounded-full border-4 border-white shadow-md bg-white" 
                                 src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=1677FF&color=fff&size=128' }}" 
                                 alt="{{ $user->name }}">
                            <button class="absolute bottom-0 right-0 p-1.5 bg-gray-100 rounded-full border border-gray-200 hover:bg-gray-200 transition-colors shadow-sm">
                                <i class="fa-solid fa-camera text-gray-600 text-xs"></i>
                            </button>
                        </div>
                        <div class="pb-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="pb-2">
                        <button class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <i class="fa-solid fa-pen-to-square mr-2"></i>Chỉnh sửa hồ sơ
                        </button>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Details -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-id-card mr-3 text-primary"></i>Thông tin cá nhân
                            </h3>
                            
                            <div class="space-y-4">
                                <div class="flex items-center group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                        <i class="fa-solid fa-user-tag text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Mã nhân viên</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->code }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                        <i class="fa-solid fa-building text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Phòng ban</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->department ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                        <i class="fa-solid fa-briefcase text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Chức vụ</p>
                                        <p class="text-sm font-medium text-gray-900">{{ strtoupper($user->role) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Info -->
                        <div class="space-y-6">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fa-solid fa-calendar-check mr-3 text-primary"></i>Thông tin hệ thống
                            </h3>

                            <div class="space-y-4">
                                <div class="flex items-center group">
                                    <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-success group-hover:bg-success group-hover:text-white transition-colors duration-200 mr-4">
                                        <i class="fa-solid fa-calendar-day text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Ngày vào làm</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->hire_date ? \Carbon\Carbon::parse($user->hire_date)->format('d/m/Y') : 'Chưa có dữ liệu' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center group">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                        <i class="fa-solid fa-shield-halved text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Trạng thái tài khoản</p>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="flex items-center group">
                                    <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-warning group-hover:bg-warning group-hover:text-white transition-colors duration-200 mr-4">
                                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Gia nhập từ</p>
                                        <p class="text-sm font-medium text-gray-900">{{ $user->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
