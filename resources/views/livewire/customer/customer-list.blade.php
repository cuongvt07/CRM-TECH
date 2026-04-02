<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Danh sách Khách hàng</h2>
            <p class="text-sm text-gray-500">Quản lý và theo dõi thông tin đối tác kinh doanh</p>
        </div>
        <button wire:click="openCreateModal" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center">
            <i class="fa-solid fa-user-plus mr-2"></i> Thêm khách hàng
        </button>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex items-center justify-between">
        <div class="relative w-96">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
            </span>
            <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:bg-white focus:ring-2 focus:ring-primary/20 focus:border-primary sm:text-sm transition duration-150 ease-in-out" placeholder="Tìm tên, mã hoặc số điện thoại...">
        </div>
        <div class="flex space-x-2">
            <button class="p-2 text-gray-400 hover:text-primary transition-colors">
                <i class="fa-solid fa-filter"></i>
            </button>
            <button class="p-2 text-gray-400 hover:text-primary transition-colors">
                <i class="fa-solid fa-file-export"></i>
            </button>
        </div>
    </div>

    {{-- Customers Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-100 uppercase tracking-tighter text-xs">
                <tr>
                    <th class="px-6 py-4">Khách hàng</th>
                    <th class="px-6 py-4">Mã KH</th>
                    <th class="px-6 py-4">Liên hệ</th>
                    <th class="px-6 py-4">Người liên hệ</th>
                    <th class="px-6 py-4">Địa chỉ</th>
                    <th class="px-6 py-4">Ghi chú</th>
                    <th class="px-6 py-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($customer->image)
                                    <img class="h-10 w-10 rounded-xl object-cover border border-gray-100" src="{{ asset('storage/' . $customer->image) }}" alt="">
                                @else
                                    <div class="h-10 w-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold border border-primary/20">
                                        {{ substr($customer->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="font-bold text-gray-900">{{ $customer->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $customer->email ?? 'Không có email' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs font-bold text-blue-600">{{ $customer->customer_code }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center text-gray-700">
                                <i class="fa-solid fa-phone text-xs mr-2 text-gray-400"></i>
                                {{ $customer->phone ?? '---' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $customer->contact_person ?? '---' }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-xs truncate text-gray-500" title="{{ $customer->address }}">
                                {{ $customer->address ?? '---' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 italic text-gray-400 text-xs">
                            {{ Str::limit($customer->note, 30) ?: '---' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md transition-colors">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>
                                <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-md transition-colors">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fa-solid fa-users-slash text-4xl text-gray-200 mb-4"></i>
                                <p>Chưa có khách hàng nào phù hợp</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $customers->links() }}
        </div>
    </div>

    {{-- Create Modal --}}
    @if($showModal)
    <div class="fixed inset-0 z-[1001] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="$set('showModal', false)"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full border border-gray-100">
                <div class="bg-primary px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-black uppercase tracking-tighter" id="modal-title">
                        <i class="fa-solid fa-user-plus mr-2"></i> THÊM KHÁCH HÀNG MỚI
                    </h3>
                    <button wire:click="$set('showModal', false)" class="text-white/80 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="bg-white px-6 py-6 scrollbar-thin scrollbar-thumb-gray-200 max-h-[80vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- Name --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Tên khách hàng *</label>
                            <input wire:model="name" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder-gray-400 font-bold text-gray-800" placeholder="Nguyễn Văn A...">
                            @error('name') <span class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</span> @enderror
                        </div>

                        {{-- Tax Code --}}
                        <div class="col-span-1">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Mã số thuế</label>
                            <input wire:model="tax_code" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-mono text-sm" placeholder="0123456789...">
                        </div>

                        {{-- Phone --}}
                        <div class="col-span-1">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Số điện thoại</label>
                            <input wire:model="phone" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="090...">
                        </div>

                        {{-- Email --}}
                        <div class="col-span-1">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Email</label>
                            <input wire:model="email" type="email" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="example@gmail.com">
                        </div>

                        {{-- Contact Person --}}
                        <div class="col-span-1">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Người liên hệ</label>
                            <input wire:model="contact_person" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all" placeholder="Ông/Bà...">
                        </div>

                        {{-- Address --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Địa chỉ</label>
                            <textarea wire:model="address" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all h-20" placeholder="Số nhà, Tên đường..."></textarea>
                        </div>

                        {{-- Image --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Hình ảnh khách hàng (Avatar)</label>
                            <div class="mt-1 flex items-center space-x-4">
                                <div class="h-16 w-16 rounded-xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center">
                                    @if($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="h-full w-full object-cover">
                                    @else
                                        <i class="fa-solid fa-camera text-gray-300"></i>
                                    @endif
                                </div>
                                <input type="file" wire:model="image" class="text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                            </div>
                        </div>

                        {{-- Note --}}
                        <div class="col-span-2">
                            <label class="block text-xs font-black text-gray-500 uppercase mb-2">Ghi chú</label>
                            <textarea wire:model="note" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all h-24" placeholder="Khách hàng VIP, thói quen..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button wire:click="$set('showModal', false)" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 font-bold transition-all">
                        HỦY
                    </button>
                    <button wire:click="save" class="px-8 py-2 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-600 font-bold transition-all">
                        LƯU KHÁCH HÀNG
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
