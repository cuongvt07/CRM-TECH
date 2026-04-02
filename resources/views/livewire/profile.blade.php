<div class="space-y-6">
    {{-- Profile Header Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="relative h-32 bg-gradient-to-r from-primary to-blue-600"></div>
        <div class="px-8 pb-6">
            <div class="relative flex justify-between items-end -mt-12 mb-6">
                <div class="flex items-end space-x-5">
                    <div class="relative">
                        <img class="h-24 w-24 rounded-full border-4 border-white shadow-md bg-white object-cover" 
                             src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=1677FF&color=fff&size=128' }}" 
                             alt="{{ $user->name }}">
                        <label class="absolute bottom-0 right-0 p-1.5 bg-gray-100 rounded-full border border-gray-200 hover:bg-gray-200 transition-colors shadow-sm cursor-pointer">
                            <i class="fa-solid fa-camera text-gray-600 text-xs"></i>
                            <input type="file" class="hidden" />
                        </label>
                    </div>
                    <div class="pb-2">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-medium text-gray-500 flex items-center gap-1">
                                <i class="fa-solid fa-id-badge text-gray-400"></i> {{ $user->code }}
                            </span>
                            <span class="text-gray-300">•</span>
                            <span class="text-xs font-medium text-gray-500 flex items-center gap-1">
                                <i class="fa-solid fa-building text-gray-400"></i> {{ $user->department->name ?? 'Tự do' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="pb-2">
                    <button class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50 transition-colors shadow-sm">
                        <i class="fa-solid fa-pen-to-square"></i>
                        Chỉnh sửa hồ sơ
                    </button>
                </div>
            </div>

            {{-- Tab Navigation --}}
            <div class="flex items-center gap-8 border-b border-gray-100">
                <button wire:click="switchTab('profile')" 
                        class="pb-4 text-sm font-semibold transition-all relative {{ $tab === 'profile' ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
                    <i class="fa-solid fa-user-tie mr-2"></i>Hồ sơ cá nhân
                    @if($tab === 'profile')
                        <div class="absolute bottom-0 left-0 w-full h-0.5 bg-primary"></div>
                    @endif
                </button>
                <button wire:click="switchTab('duties')" 
                        class="pb-4 text-sm font-semibold transition-all relative {{ $tab === 'duties' ? 'text-primary' : 'text-gray-400 hover:text-gray-600' }}">
                    <i class="fa-solid fa-book-open mr-2"></i>Nhiệm vụ & Chức năng
                    @if($tab === 'duties')
                        <div class="absolute bottom-0 left-0 w-full h-0.5 bg-primary"></div>
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Tab Content --}}
    <div class="transition-all duration-300">
        @if($tab === 'profile')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Personal Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-address-card text-primary"></i>
                            Thông tin cá nhân
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Mã nhân viên</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $user->code }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Chức vụ</p>
                                @php
                                    $roleLabels = [
                                        'admin' => 'IT',
                                        'director' => 'Giám đốc',
                                        'manager' => 'Quản lý',
                                        'supervisor' => 'Quản đốc',
                                        'team_leader' => 'Tổ trưởng',
                                        'employee' => 'Nhân viên',
                                    ];
                                @endphp
                                <p class="text-sm font-semibold text-gray-900 italic text-primary">
                                    {{ $roleLabels[$user->role] ?? 'Nhân viên' }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-gray-50">
                            <div class="flex items-center group">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                    <i class="fa-solid fa-phone text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Số điện thoại</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->phone }}</p>
                                </div>
                            </div>
                            <div class="flex items-center group">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                    <i class="fa-solid fa-envelope text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Email cá nhân</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->email ?? '—' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center group">
                                <div class="w-9 h-9 rounded-lg bg-blue-50 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors duration-200 mr-4">
                                    <i class="fa-solid fa-building-user text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Phòng ban</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->department->name ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- System Information --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-gears text-gray-500"></i>
                            Thông tin hệ thống
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Trạng thái tài khoản</p>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold {{ $user->status === 'active' ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/10' : 'bg-red-50 text-red-700 ring-1 ring-red-600/10' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                    {{ $user->status === 'active' ? 'HOẠT ĐỘNG' : 'TẠM KHÓA' }}
                                </span>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-1">Ngày vào làm</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $user->hire_date ? \Carbon\Carbon::parse($user->hire_date)->format('d/m/Y') : 'Chưa cập nhật' }}</p>
                            </div>
                        </div>

                        <div class="space-y-4 pt-4 border-t border-gray-50">
                            <div class="flex items-center group">
                                <div class="w-9 h-9 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600 group-hover:bg-orange-600 group-hover:text-white transition-colors duration-200 mr-4">
                                    <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Gia nhập từ</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $user->created_at->format('d/m/Y') }} ({{ $user->created_at->diffForHumans() }})</p>
                                </div>
                            </div>
                            <div class="flex items-center group">
                                <div class="w-9 h-9 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-200 mr-4">
                                    <i class="fa-solid fa-key text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Mật khẩu</p>
                                    <p class="text-xs text-gray-500 italic">Đã bảo mật • Cập nhật gần nhất 2 tháng trước</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($tab === 'duties')
            @if($department)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Left Column: Dept Summary --}}
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
                                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-sitemap text-primary"></i>
                                    {{ $department->name }}
                                </h2>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Mã bộ phận</p>
                                    <p class="font-mono text-xs inline-block bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $department->code }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Trưởng phòng</p>
                                    <p class="text-sm font-semibold text-primary italic">{{ $department->head->name ?? '—' }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mb-1">Liên hệ nội bộ</p>
                                    <p class="text-sm text-gray-700 font-medium">
                                        <i class="fa-solid fa-phone-flip mr-1 text-gray-400 text-xs"></i> {{ $department->phone ?? '—' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-amber-50 rounded-xl p-5 border border-amber-100 text-sm text-amber-800 flex gap-3">
                            <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                            <p>Mọi nhân sự có nghĩa vụ tuân thủ đúng nội quy này. Vi phạm sẽ được xử lý theo quy chế công ty.</p>
                        </div>
                    </div>

                    {{-- Right Column: Main Duties Content --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 min-h-[500px] flex flex-col">
                            <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                                <h2 class="font-semibold text-gray-800 flex items-center gap-2">
                                    <i class="fa-solid fa-book-open text-amber-500"></i>
                                    Chi tiết Nhiệm vụ & Chức năng
                                </h2>
                                <button onclick="window.print()" class="text-gray-400 hover:text-primary transition-colors">
                                    <i class="fa-solid fa-print"></i>
                                </button>
                            </div>
                            <div class="p-8 flex-1">
                                @if($department->duties)
                                    <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-wrap font-sans">{{ $department->duties }}</div>
                                @else
                                    <div class="h-full flex flex-col items-center justify-center text-center space-y-3 py-20">
                                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                                            <i class="fa-solid fa-note-sticky text-2xl"></i>
                                        </div>
                                        <p class="text-gray-400 italic">Dữ liệu nhiệm vụ đang được cập nhật...</p>
                                    </div>
                                @endif
                            </div>
                            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
                                <p class="text-[11px] text-gray-400 text-right">Cập nhật lần cuối: {{ $department->updated_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-20 flex flex-col items-center justify-center text-center">
                    <i class="fa-solid fa-building-circle-exclamation text-4xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500 italic">Bạn chưa được phân bổ vào phòng ban chính thức.</p>
                </div>
            @endif
        @endif
    </div>
</div>
