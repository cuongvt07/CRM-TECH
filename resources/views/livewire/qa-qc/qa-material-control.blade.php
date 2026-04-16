<div class="p-6 bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <div>
            <h1 class="text-xl font-black text-slate-800 uppercase tracking-tight">Cấu hình & Duyệt định mức (BOM)</h1>
            <p class="text-slate-500 text-xs">Phê duyệt tiêu chuẩn kỹ thuật và khóa dữ liệu định mức</p>
        </div>
        @if($selectedProductId)
            <button wire:click="$set('selectedProductId', null)" class="px-3 py-1.5 text-slate-500 hover:text-slate-800 text-sm font-bold flex items-center">
                <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại danh sách
            </button>
        @endif
    </div>

    @if(!$selectedProductId)
        <!-- LIST VIEW -->
        <div class="mb-6 max-w-md">
            <div class="relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchProduct" 
                    class="w-full h-11 pl-10 pr-4 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-all"
                    placeholder="Tìm sản phẩm (Mã/Tên) để cấu hình BOM..."
                >
                <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"></i>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($products as $product)
                <div 
                    wire:click="selectProduct({{ $product->id }})"
                    class="bg-white p-5 rounded-xl shadow-sm border border-slate-200 hover:border-emerald-500 hover:shadow-md transition-all cursor-pointer group relative overflow-hidden"
                >
                    <div class="flex justify-between items-start mb-3">
                        <div class="w-10 h-10 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold group-hover:bg-emerald-100 group-hover:text-emerald-600 transition-colors">
                            {{ substr($product->name, 0, 1) }}
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">#{{ $product->code }}</span>
                            @php
                                $statusMap = [
                                    'draft' => ['label' => 'Bản thảo', 'class' => 'text-slate-500 bg-slate-50'],
                                    'approved' => ['label' => 'Đã Duyệt', 'class' => 'text-emerald-600 bg-emerald-50'],
                                ];
                                $st = $statusMap[$product->bom_status] ?? $statusMap['draft'];
                            @endphp
                            <span class="mt-1 px-2 py-0.5 rounded text-[8px] font-black uppercase {{ $st['class'] }} border border-current opacity-70">
                                {{ $st['label'] }}
                            </span>
                        </div>
                    </div>
                    <h3 class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors truncate">{{ $product->name }}</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Đơn vị: {{ $product->unit }} | {{ count($product->boms) }} vật tư</p>
                    
                    @if($product->bom_approved_at)
                        <div class="mt-4 pt-3 border-t border-slate-50 text-[9px] text-slate-400 flex flex-col space-y-1">
                            <div class="flex items-center">
                                <i class="fa-solid fa-clock-check mr-1.5"></i> Duyệt: {{ $product->bom_approved_at->format('d/m/Y H:i') }}
                            </div>
                            @if($product->bomApprover)
                            <div class="flex items-center text-emerald-600 font-bold">
                                <i class="fa-solid fa-user-check mr-1.5"></i> Bởi: {{ $product->bomApprover->name }}
                            </div>
                            @endif
                        </div>
                    @endif

                    <div class="absolute right-0 bottom-0 p-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fa-solid fa-chevron-right text-emerald-300"></i>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- CONFIGURATION VIEW (Selected Product) -->
        <div class="space-y-6">
            <!-- TOP SECTION: Product Info & Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center space-y-4 md:space-y-0">
                    <div class="flex items-center space-x-5">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-2xl font-black">
                            {{ substr($selectedProduct->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="bg-slate-800 text-white text-[9px] font-black px-1.5 py-0.5 rounded tracking-widest uppercase">{{ $selectedProduct->code }}</span>
                                <h2 class="text-xl font-black text-slate-800">{{ $selectedProduct->name }}</h2>
                            </div>
                            <div class="flex items-center space-x-4 text-xs text-slate-500 font-bold">
                                <span><i class="fa-solid fa-industry mr-1.5 text-slate-300"></i>Hãng: {{ $selectedProduct->brand ?? 'N/A' }}</span>
                                <span><i class="fa-solid fa-ruler-combined mr-1.5 text-slate-300"></i>Đơn vị gốc: {{ $selectedProduct->unit }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col items-end space-y-3">
                        @if($selectedProduct->bom_status === 'approved')
                            <div class="flex items-center space-x-3">
                                <div class="text-right">
                                    <div class="text-[9px] font-black text-emerald-600 uppercase tracking-widest leading-none">ĐÃ CHẤP NHẬN</div>
                                    <div class="text-[10px] text-slate-400 font-medium mt-1">{{ $selectedProduct->bom_approved_at?->format('d/m/Y H:i') }}</div>
                                    @if($selectedProduct->bomApprover)
                                        <div class="text-[10px] font-bold text-emerald-700 mt-0.5">Bởi: {{ $selectedProduct->bomApprover->name }}</div>
                                    @endif
                                </div>
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg shadow-sm">
                                    <i class="fa-solid fa-check-double"></i>
                                </div>
                            </div>
                            
                            @if(Auth::user()->role === 'admin')
                                <button 
                                    wire:click="unapproveBom({{ $selectedProduct->id }})" 
                                    wire:confirm="Bạn có chắc chắn muốn HỦY DUYỆT? Dữ liệu định mức sẽ có thể chỉnh sửa lại."
                                    class="text-[10px] font-bold text-red-400 hover:text-red-600 border-b border-red-200 border-dashed hover:border-red-500 transition-all uppercase"
                                >
                                    <i class="fa-solid fa-unlock mr-1"></i> Admin: Hủy duyệt định mức
                                </button>
                            @endif
                        @else
                            <button 
                                wire:click="approveBom({{ $selectedProduct->id }})" 
                                class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-black text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 uppercase tracking-widest flex items-center group"
                            >
                                <i class="fa-solid fa-shield-check mr-2 group-hover:scale-110 transition-transform"></i>
                                Chấp nhận & Khóa định mức
                            </button>
                            <span class="text-[9px] text-slate-400 font-bold uppercase tracking-tight">* Cần phê duyệt để bộ phận SX có thể phát lệnh</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- BOTTOM SECTION: BOM Configuration Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden min-h-[400px]">
                <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="font-black text-slate-700 uppercase tracking-widest text-xs">Cấu hình định mức (Vật tư tiêu hao)</h3>
                    @if($selectedProduct->bom_status !== 'approved')
                        <div class="flex items-center text-[10px] font-bold text-slate-400 italic">
                            <i class="fa-solid fa-info-circle mr-1.5 overflow-visible"></i> Dữ liệu đang mở khóa - Có thể chỉnh sửa
                        </div>
                    @endif
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto rounded-xl border border-slate-100 mb-6">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 text-[10px] uppercase font-black tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Mã NVL</th>
                                    <th class="px-6 py-4">Tên Nguyên vật liệu</th>
                                    <th class="px-6 py-4 text-right">Số lượng (Định mức)</th>
                                    <th class="px-6 py-4">Đơn vị</th>
                                    <th class="px-6 py-4">Hãng sản xuất</th>
                                    @if($selectedProduct->bom_status !== 'approved')
                                        <th class="px-4 py-4 text-center">Xóa</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($selectedProduct->boms as $bom)
                                    <tr class="hover:bg-slate-50/30 transition-colors">
                                        <td class="px-6 py-4 font-mono text-xs text-slate-400 group relative">
                                            {{ $bom->material?->code }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-sm">{{ $bom->material?->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-sm font-black text-slate-900 leading-none">@nfmt($bom->quantity)</span>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-bold text-slate-500 uppercase">
                                            {{ $bom->unit }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-xs text-slate-500">{{ $bom->material?->brand ?? '--' }}</span>
                                        </td>
                                        @if($selectedProduct->bom_status !== 'approved')
                                            <td class="px-4 py-4 text-center">
                                                <button wire:click="removeMaterial({{ $bom->id }})" class="text-slate-300 hover:text-red-500 transition-all p-2">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ $selectedProduct->bom_status !== 'approved' ? 6 : 5 }}" class="px-6 py-12 text-center text-slate-300 italic text-xs">
                                            Sản phẩm này chưa được cấu hình định mức vật tư
                                        </td>
                                    </tr>
                                @endforelse
                                
                                <!-- INLINE ADD MATERIAL ROW (Only if not approved) -->
                                @if($selectedProduct->bom_status !== 'approved')
                                    <tr class="bg-emerald-50/40 border-t-[3px] border-emerald-100/80 shadow-inner group relative">
                                        <td class="px-6 py-4" colspan="2">
                                            <div class="relative w-full">
                                                <input 
                                                    type="text" 
                                                    wire:model.live.debounce.300ms="searchMaterial" 
                                                    class="w-full h-[38px] pl-9 pr-3 bg-white border border-emerald-200 rounded-lg text-xs font-bold focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm transition-all placeholder:font-normal placeholder:text-slate-400"
                                                    placeholder="TÌM KIẾM NVL (MÃ/TÊN)..."
                                                >
                                                <i class="fa-solid fa-search absolute left-3 top-1/2 -translate-y-1/2 text-emerald-400 text-xs"></i>
                                                
                                                @if($searchMaterial && count($availableMaterials) > 0)
                                                    <div class="absolute z-50 w-full min-w-[300px] bg-white border border-emerald-200 rounded-xl shadow-2xl overflow-hidden py-1 max-h-60 overflow-y-auto left-0 bottom-full mb-2">
                                                        @foreach($availableMaterials as $mat)
                                                            <button 
                                                                type="button" 
                                                                wire:click="$set('newMaterialId', {{ $mat->id }})" 
                                                                class="w-full text-left px-4 py-2 hover:bg-emerald-50 flex justify-between items-center transition-colors {{ $newMaterialId == $mat->id ? 'bg-emerald-100 text-emerald-800' : '' }}"
                                                            >
                                                                <div class="truncate mr-3">
                                                                    <div class="text-xs font-bold leading-none text-slate-700 truncate">{{ $mat->name }}</div>
                                                                    <div class="text-[9px] text-slate-400 font-mono mt-1.5 truncate">{{ $mat->code }} | Hãng: <span class="text-emerald-600 font-bold">{{ $mat->brand ?? 'N/A' }}</span></div>
                                                                </div>
                                                                <span class="text-[9px] bg-slate-100 px-1.5 py-0.5 rounded font-black text-slate-500 shrink-0">{{ $mat->unit }}</span>
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                            @error('newMaterialId') <p class="mt-1 text-[9px] text-red-500 font-bold uppercase tracking-tight absolute">{{ $message }}</p> @enderror
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <input type="number" step="0.001" wire:model="newQuantity" class="w-full max-w-[100px] float-right h-[38px] px-2 text-right bg-white border border-emerald-200 rounded-lg text-xs font-black focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm" placeholder="S.Lượng">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" wire:model="newUnit" class="w-[70px] h-[38px] px-2 bg-white border border-emerald-200 rounded-lg text-xs font-bold focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 shadow-sm uppercase placeholder:font-normal placeholder:capitalize" placeholder="ĐVT">
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $selectedMatBrand = '--';
                                                if ($newMaterialId) {
                                                    $matInfo = collect($availableMaterials)->firstWhere('id', $newMaterialId);
                                                    if (!$matInfo) {
                                                        $matInfo = \App\Models\Product::find($newMaterialId);
                                                    }
                                                    $selectedMatBrand = $matInfo?->brand ?: 'N/A';
                                                }
                                            @endphp
                                            <div class="text-xs text-emerald-700 font-black bg-white px-3 py-2 rounded-lg border border-emerald-200/60 shadow-sm w-full truncate max-w-[120px]" title="{{ $selectedMatBrand }}">
                                                {{ $selectedMatBrand }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <button 
                                                wire:click="addMaterial" 
                                                class="h-[38px] px-5 bg-emerald-600 text-white rounded-lg font-black text-[10.5px] hover:bg-emerald-700 transition-all shadow-md shadow-emerald-200 uppercase tracking-widest min-w-max flex items-center justify-center mx-auto group"
                                            >
                                                Lưu <i class="fa-solid fa-download ml-2 group-hover:-translate-y-0.5 transition-transform"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        .customize-scrollbar::-webkit-scrollbar { width: 4px; }
        .customize-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .customize-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</div>
