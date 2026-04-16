<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Lập kế hoạch sản xuất mới</h2>
            <p class="text-sm text-gray-500 mt-1">Khởi tạo lệnh sản xuất và kiểm tra định mức vật tư.</p>
        </div>
        <a href="{{ route('production.index') }}" class="text-gray-500 hover:text-gray-800 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i> Quay lại
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- FORM COLUMN -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <form wire:submit.prevent="save" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Selection -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Sản phẩm cần sản xuất</label>
                            <select wire:model.live="productId" wire:change="checkMaterials" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary">
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}">{{ $p->code }} - {{ $p->name }} ({{ $p->bom_status === 'approved' ? 'Đã duyệt' : 'Chưa duyệt' }})</option>
                                @endforeach
                            </select>
                            
                            @if($productId)
                                @php $currentProd = $products->find($productId); @endphp
                                @if($currentProd && $currentProd->bom_status !== 'approved')
                                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-xl flex items-center text-red-700 text-xs font-bold animate-pulse">
                                        <i class="fa-solid fa-triangle-exclamation mr-2"></i>
                                        CẢNH BÁO: BOM của sản phẩm này chưa được QA chấp nhận. Bạn không thể phát lệnh sản xuất!
                                    </div>
                                @endif
                            @endif

                            @error('productId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Số lượng thành phẩm</label>
                            <div class="relative">
                                <input type="number" wire:model.live="quantity" min="1" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary pl-10">
                                <i class="fa-solid fa-box-open absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                            @error('quantity') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Assingee -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nhân viên phụ trách</label>
                            <select wire:model="assignedTo" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary">
                                <option value="">-- Chọn nhân viên --</option>
                                @foreach($employees as $e)
                                    <option value="{{ $e->id }}">{{ $e->name }} ({{ $e->department ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Dates -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày bắt đầu dự kiến</label>
                            <input type="date" wire:model="startDate" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ngày hoàn thành dự kiến</label>
                            <input type="date" wire:model="endDate" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary">
                            @error('endDate') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Note -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ghi chú lệnh sản xuất</label>
                            <textarea wire:model="note" rows="3" class="w-full rounded-xl border-gray-200 focus:ring-primary focus:border-primary" placeholder="Yêu cầu đặc biệt, lưu ý kỹ thuật..."></textarea>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full py-4 bg-primary hover:bg-primary-dark text-white rounded-xl font-bold text-lg shadow-lg shadow-primary/20 transition-all flex items-center justify-center">
                            <i class="fa-solid fa-paper-plane mr-2"></i> PHÁT LỆNH SẢN XUẤT
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- MATERIAL CHECK COLUMN -->
        <div class="space-y-6">
            <div class="bg-gray-800 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                <div class="absolute -right-10 -top-10 text-white/5 text-8xl rotate-12">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                
                <h3 class="text-lg font-bold mb-6 flex items-center">
                    <i class="fa-solid fa-vial mr-2 text-primary"></i> ĐỊNH MỨC VẬT TƯ (BOM)
                </h3>

                @if($materialCheck)
                    @if($materialCheck['status'] === 'no_bom')
                        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-4 text-yellow-500 text-sm italic">
                            Sản phẩm này chưa được cấu hình định mức nguyên vật liệu (BOM).
                        </div>
                    @else
                        <div class="space-y-4">
                            @php $isInsuf = $materialCheck['status'] === 'insufficient'; @endphp
                            
                            <div class="flex items-center justify-between p-3 rounded-xl {{ $isInsuf ? 'bg-red-500/20 border-red-500/30 text-red-400' : 'bg-green-500/20 border-green-500/30 text-green-400' }} border">
                                <span class="text-xs font-bold uppercase">{{ $isInsuf ? 'Thiếu hụt vật tư' : 'Sẵn sàng sản xuất' }}</span>
                                <i class="fa-solid {{ $isInsuf ? 'fa-triangle-exclamation' : 'fa-circle-check' }}"></i>
                            </div>

                            <div class="bg-white/5 rounded-xl border border-white/10 overflow-hidden">
                                <table class="w-full text-xs">
                                    <thead class="bg-white/10 text-gray-400">
                                        <tr>
                                            <th class="px-4 py-2 text-left">Vật tư</th>
                                            <th class="px-4 py-2 text-right">Cần</th>
                                            <th class="px-4 py-2 text-right text-gray-400 font-normal">Tồn</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5">
                                        @foreach($materialCheck['missing'] as $m)
                                            <tr class="text-red-400">
                                                <td class="px-4 py-3">{{ $m['name'] }}</td>
                                                <td class="px-4 py-3 text-right font-bold">@nfmt($m['required'])</td>
                                                <td class="px-4 py-3 text-right opacity-60">@nfmt($m['current'])</td>
                                            </tr>
                                        @endforeach
                                        
                                        {{-- Hiển thị cả vật tư đủ --}}
                                        @php 
                                            $prod = \App\Models\Product::with('boms.material.inventory')->find($productId);
                                            $missingIds = collect($materialCheck['missing'])->pluck('name')->toArray();
                                        @endphp
                                        @if($prod)
                                            @foreach($prod->boms as $bom)
                                                @if(!in_array($bom->material->name, $missingIds))
                                                    <tr class="text-green-400">
                                                        <td class="px-4 py-3">{{ $bom->material->name }}</td>
                                                        <td class="px-4 py-3 text-right font-bold">@nfmt($bom->quantity * $quantity)</td>
                                                        <td class="px-4 py-3 text-right opacity-60">@nfmt($bom->material->inventory?->quantity ?? 0)</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            @if($isInsuf)
                                <p class="text-[10px] text-gray-400 italic">
                                    * Vui lòng nhập thêm vật tư hoặc điều chỉnh số lượng sản xuất.
                                </p>
                            @endif
                        </div>
                    @endif
                @else
                    <div class="py-10 text-center text-gray-500">
                        <i class="fa-solid fa-magnifying-glass text-3xl mb-3 block opacity-20"></i>
                        <p class="text-sm">Chọn sản phẩm để kiểm tra vật tư.</p>
                    </div>
                @endif
            </div>

            <!-- Dashboard Info -->
            <div class="bg-white rounded-2xl border border-gray-100 p-6 space-y-4">
                <h3 class="font-bold text-gray-800 text-sm border-b pb-2 uppercase tracking-wide">Thống kê hiện hành</h3>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Đang sản xuất:</span>
                    <span class="font-bold text-orange-500">{{ \App\Models\ProductionOrder::where('status', 'in_progress')->count() }} lệnh</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Chờ QC:</span>
                    <span class="font-bold text-purple-500">{{ \App\Models\ProductionOrder::where('status', 'qc')->count() }} lệnh</span>
                </div>
            </div>
        </div>
    </div>
</div>
