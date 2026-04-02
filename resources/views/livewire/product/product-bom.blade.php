<div class="space-y-6 max-w-5xl mx-auto py-6">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <a wire:navigate href="{{ route('products.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Cấu hình Định mức (BOM): <span class="text-primary">{{ $product->name }}</span></h2>
        </div>
        <div class="bg-blue-50 px-4 py-2 rounded-lg border border-blue-100 flex items-center space-x-2">
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider">Đơn vị gốc:</span>
            <span class="text-sm font-black text-blue-800">{{ $product->unit }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Add Material Section -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fa-solid fa-plus-circle text-green-500 mr-2"></i> Thêm vật tư
                </h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tìm vật tư (Mã/Tên)</label>
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" class="w-full pl-10 rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm" placeholder="Nhập để tìm...">
                            <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    @if($search || count($availableMaterials) > 0)
                        <div class="bg-gray-50 rounded-xl p-2 max-h-48 overflow-y-auto space-y-1 border border-gray-100">
                            @foreach($availableMaterials as $mat)
                                <button type="button" wire:click="$set('selectedMaterialId', {{ $mat->id }})" class="w-full text-left p-2 rounded-lg text-sm hover:bg-white hover:shadow-sm transition-all flex justify-between items-center {{ $selectedMaterialId == $mat->id ? 'bg-blue-50 text-blue-700 border border-blue-200' : 'text-gray-600' }}">
                                    <span>[{{ $mat->code }}] {{ $mat->name }}</span>
                                    <span class="text-[10px] bg-gray-200 px-1.5 py-0.5 rounded text-gray-500">{{ $mat->unit }}</span>
                                </button>
                            @endforeach
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Định mức <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" wire:model="quantity" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm">
                            @error('quantity') <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">ĐVT tiêu hao</label>
                            <input type="text" wire:model="unit" class="w-full rounded-xl border-gray-200 focus:border-primary focus:ring-primary shadow-sm text-sm" placeholder="Mặc định">
                        </div>
                    </div>

                    <button wire:click="addMaterial" class="w-full py-3 bg-primary text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 active:scale-95 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-plus"></i>
                        <span>CẬP NHẬT ĐỊNH MỨC</span>
                    </button>
                    @error('selectedMaterialId') <span class="text-xs text-red-500 text-center block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-xl border border-dashed border-gray-300">
                <p class="text-xs text-gray-500 leading-relaxed">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Định mức (BOM) quy định số lượng nguyên vật liệu hoặc vật tư phụ cần thiết để tạo ra 1 đơn vị sản phẩm này.
                    Khi lệnh sản xuất hoàn thành, hệ thống sẽ tự động trừ kho nguyên liệu theo tỉ lệ này.
                </p>
            </div>
        </div>

        <!-- BOM List Section -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Danh sách Nguyên vật liệu tiêu hao</h3>
                    <span class="text-xs text-gray-400">{{ count($product->boms) }} loại vật tư</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-500 font-bold text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4">Mã/Vật tư</th>
                                <th class="px-6 py-4">Phân loại</th>
                                <th class="px-4 py-4 text-center">Định mức</th>
                                <th class="px-4 py-4 text-center">ĐVT</th>
                                <th class="px-6 py-4 text-right">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($product->boms as $bom)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">{{ $bom->material?->name }}</div>
                                        <div class="text-[10px] text-gray-400 font-mono">{{ $bom->material?->code }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase transition-colors {{ $bom->material?->warehouse?->code === 'RAW_MAT' ? 'bg-orange-100 text-orange-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ $bom->material?->warehouse?->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center font-black text-gray-800 text-lg">
                                        {{ number_format($bom->quantity, 2) }}
                                    </td>
                                    <td class="px-4 py-4 text-center text-gray-500 font-medium">
                                        {{ $bom->unit }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <button wire:click="removeMaterial({{ $bom->id }})" wire:confirm="Xóa khỏi định mức?" class="text-gray-300 hover:text-red-500 transition-colors">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <i class="fa-solid fa-cubes text-4xl text-gray-200 mb-4"></i>
                                            <p class="text-gray-400 font-medium">Chưa cấu hình định mức cho thành phẩm này.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
