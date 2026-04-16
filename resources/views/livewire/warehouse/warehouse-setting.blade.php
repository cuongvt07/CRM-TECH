<div class="p-6 bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-slate-200">
        <div>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Cấu hình Hệ thống Kho</h1>
            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">Thiết lập kho, danh mục và đơn vị tiêu chuẩn</p>
        </div>
        <div class="flex space-x-1 p-1 bg-slate-100 rounded-lg">
            <button 
                wire:click="setTab('warehouses')" 
                class="px-4 py-1.5 rounded-md text-xs font-black uppercase transition-all {{ $activeTab === 'warehouses' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}"
            >
                Nhà kho
            </button>
            <button 
                wire:click="setTab('categories')" 
                class="px-4 py-1.5 rounded-md text-xs font-black uppercase transition-all {{ $activeTab === 'categories' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}"
            >
                Danh mục
            </button>
            <button 
                wire:click="setTab('units')" 
                class="px-4 py-1.5 rounded-md text-xs font-black uppercase transition-all {{ $activeTab === 'units' ? 'bg-white text-orange-600 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}"
            >
                Đơn vị tính
            </button>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- FORM COLUMN -->
        <div class="col-span-12 lg:col-span-4 translate-y-0 sticky top-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                @if($activeTab === 'warehouses')
                    <h3 class="text-sm font-black text-slate-800 uppercase mb-4">{{ $warehouseId ? 'Cập nhật kho' : 'Thêm kho mới' }}</h3>
                    <form wire:submit.prevent="saveWarehouse" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Tên kho</label>
                            <input type="text" wire:model="warehouseName" class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                            @error('warehouseName') <p class="text-[10px] text-red-500 mt-1 uppercase font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Mã định danh (Code)</label>
                            <input type="text" wire:model="warehouseCode" class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-sm font-mono focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500" placeholder="vd: RAW_MAT">
                            @error('warehouseCode') <p class="text-[10px] text-red-500 mt-1 uppercase font-bold">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Mô tả</label>
                            <textarea wire:model="warehouseDescription" rows="3" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500"></textarea>
                        </div>
                        <div class="pt-2 flex space-x-2">
                            <button type="submit" class="flex-1 h-11 bg-orange-600 text-white rounded-xl font-black text-xs uppercase shadow-lg shadow-orange-100 hover:bg-orange-700 transition-all">Lưu cấu hình</button>
                            @if($warehouseId)
                                <button type="button" wire:click="$reset(['warehouseId','warehouseName','warehouseCode','warehouseDescription'])" class="px-4 h-11 bg-slate-100 text-slate-500 rounded-xl font-black text-xs uppercase hover:bg-slate-200">Hủy</button>
                            @endif
                        </div>
                    </form>

                @elseif($activeTab === 'categories')
                    <h3 class="text-sm font-black text-slate-800 uppercase mb-4">{{ $categoryId ? 'Cập nhật danh mục' : 'Thêm danh mục SP' }}</h3>
                    <form wire:submit.prevent="saveCategory" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Tên danh mục</label>
                            <input type="text" wire:model="categoryName" class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Mô tả</label>
                            <textarea wire:model="categoryDescription" rows="3" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500"></textarea>
                        </div>
                        <div class="pt-2 flex space-x-2">
                            <button type="submit" class="flex-1 h-11 bg-orange-600 text-white rounded-xl font-black text-xs uppercase shadow-lg shadow-orange-100 hover:bg-orange-700 transition-all">Lưu danh mục</button>
                            @if($categoryId)
                                <button type="button" wire:click="$reset(['categoryId','categoryName','categoryDescription'])" class="px-4 h-11 bg-slate-100 text-slate-500 rounded-xl font-black text-xs uppercase hover:bg-slate-200">Hủy</button>
                            @endif
                        </div>
                    </form>

                @elseif($activeTab === 'units')
                    <h3 class="text-sm font-black text-slate-800 uppercase mb-4">{{ $unitId ? 'Cập nhật đơn vị' : 'Thêm đơn vị tính' }}</h3>
                    <form wire:submit.prevent="saveUnit" class="space-y-4">
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Tên đơn vị (vd: mét, kg, hộp)</label>
                            <input type="text" wire:model="unitName" class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-sm font-bold focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Mô tả chi tiết</label>
                            <input type="text" wire:model="unitDescription" class="w-full h-11 px-4 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
                        </div>
                        <div class="pt-2 flex space-x-2">
                            <button type="submit" class="flex-1 h-11 bg-orange-600 text-white rounded-xl font-black text-xs uppercase shadow-lg shadow-orange-100 hover:bg-orange-700 transition-all">Lưu đơn vị</button>
                            @if($unitId)
                                <button type="button" wire:click="$reset(['unitId','unitName','unitDescription'])" class="px-4 h-11 bg-slate-100 text-slate-500 rounded-xl font-black text-xs uppercase hover:bg-slate-200">Hủy</button>
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>

        <!-- LIST COLUMN -->
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        @if($activeTab === 'warehouses')
                            <tr>
                                <th class="px-6 py-4">Mã kho</th>
                                <th class="px-6 py-4">Tên hiển thị</th>
                                <th class="px-6 py-4">Mô tả</th>
                                <th class="px-6 py-4 text-right">Thao tác</th>
                            </tr>
                        @elseif($activeTab === 'categories')
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Tên danh mục</th>
                                <th class="px-6 py-4">Mô tả</th>
                                <th class="px-6 py-4 text-right">Thao tác</th>
                            </tr>
                        @elseif($activeTab === 'units')
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Đơn vị</th>
                                <th class="px-6 py-4">Ghi chú</th>
                                <th class="px-6 py-4 text-right">Thao tác</th>
                            </tr>
                        @endif
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @if($activeTab === 'warehouses')
                            @foreach($warehouses as $wh)
                                <tr class="hover:bg-slate-50/50 group">
                                    <td class="px-6 py-4 font-mono text-xs text-orange-600 font-bold uppercase">{{ $wh->code }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-700">{{ $wh->name }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $wh->description }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button wire:click="editWarehouse({{ $wh->id }})" class="text-slate-400 hover:text-blue-500 transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button wire:click="delete('warehouse', {{ $wh->id }})" wire:confirm="Xóa kho có thể ảnh hưởng đến SP thuộc kho này. Tiếp tục?" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @elseif($activeTab === 'categories')
                            @foreach($categories as $cat)
                                <tr class="hover:bg-slate-50/50 group">
                                    <td class="px-6 py-4 text-xs text-slate-300">#{{ $cat->id }}</td>
                                    <td class="px-6 py-4 font-bold text-slate-700">{{ $cat->name }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $cat->description }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button wire:click="editCategory({{ $cat->id }})" class="text-slate-400 hover:text-blue-500 transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button wire:click="delete('category', {{ $cat->id }})" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @elseif($activeTab === 'units')
                            @foreach($units as $unit)
                                <tr class="hover:bg-slate-50/50 group">
                                    <td class="px-6 py-4 text-xs text-slate-300">#{{ $unit->id }}</td>
                                    <td class="px-6 py-4 font-black text-slate-700 uppercase tracking-wide">{{ $unit->name }}</td>
                                    <td class="px-6 py-4 text-xs text-slate-400">{{ $unit->description }}</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <button wire:click="editUnit({{ $unit->id }})" class="text-slate-400 hover:text-blue-500 transition-colors"><i class="fa-solid fa-pen-to-square"></i></button>
                                        <button wire:click="delete('unit', {{ $unit->id }})" class="text-slate-400 hover:text-red-500 transition-colors"><i class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                @if(count(${$activeTab}) === 0)
                    <div class="p-12 text-center text-slate-300 italic text-xs uppercase tracking-widest bg-slate-50">Chưa có dữ liệu cấu hình chuyên sâu</div>
                @endif
            </div>
        </div>
    </div>
</div>
