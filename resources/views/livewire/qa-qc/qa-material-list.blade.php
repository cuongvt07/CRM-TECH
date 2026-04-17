<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Danh mục nguyên vật liệu (QA/QC)</h2>
            <p class="text-sm text-gray-500">Quản lý và kiểm tra chất lượng đầu vào của nguyên vật liệu kho</p>
        </div>
        <div class="flex space-x-3">
            <button wire:click="printSelected" class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg shadow-sm transition-all flex items-center font-bold text-sm">
                <i class="fa-solid fa-print mr-2 text-primary"></i> IN PHIẾU CHỌN
            </button>
            <button 
                wire:click="deleteSelected" 
                wire:confirm="Bạn có chắc chắn muốn xóa các mục đã chọn? Thao tác này không thể hoàn tác."
                class="bg-white border border-red-200 hover:bg-red-50 text-red-600 px-4 py-2 rounded-lg shadow-sm transition-all flex items-center font-bold text-sm"
            >
                <i class="fa-solid fa-trash-can mr-2"></i> XÓA
            </button>
            <button wire:click="openCreateModal" class="bg-primary hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md transition-all flex items-center font-bold text-sm">
                <i class="fa-solid fa-plus-circle mr-2"></i> THÊM MỚI NVL
            </button>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4 flex-1">
            <div class="relative w-80">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                </span>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 sm:text-sm transition duration-150" placeholder="Tìm theo mã hoặc tên NVL...">
            </div>
            
            <select wire:model.live="filterInspectionStatus" class="w-40 px-2.5 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition duration-150 text-gray-600 font-medium">
                <option value="">Hiện trạng (Tất cả)</option>
                <option value="pending">Chưa kiểm tra</option>
                <option value="inspecting">Đang kiểm tra</option>
            </select>

            <select wire:model.live="filterApprovalStatus" class="w-40 px-2.5 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition duration-150 text-gray-600 font-medium">
                <option value="">Tình trạng (Tất cả)</option>
                <option value="pending">Chưa duyệt</option>
                <option value="approved">Đã duyệt</option>
                <option value="rejected">Từ chối</option>
            </select>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 text-gray-600 font-bold border-b border-gray-100 uppercase tracking-tighter text-xs">
                <tr>
                    <th class="px-6 py-4 w-10">
                        <input type="checkbox" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                    </th>
                    <th class="px-6 py-4">Mã NVL</th>
                    <th class="px-6 py-4">Tên NVL</th>
                    <th class="px-6 py-4">ĐVT</th>
                    <th class="px-6 py-4 text-center">SL Nhập / Tồn Kho</th>
                    <th class="px-6 py-4 text-center">Hiện trạng</th>
                    <th class="px-6 py-4 text-center">Tình trạng</th>
                    <th class="px-6 py-4">Ghi chú</th>
                    <th class="px-6 py-4 text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4">
                            <input type="checkbox" wire:model="selectedItems" value="{{ $product->id }}" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                        </td>
                        <td class="px-6 py-4 font-mono text-xs font-bold text-emerald-600">{{ $product->code }}</td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $product->name }}</div>
                            <div class="text-[10px] text-gray-400">Master List</div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 uppercase">{{ $product->unit }}</td>
                        <td class="px-6 py-4 text-center">
                            @php $latest = $product->latestImportTransaction; @endphp
                            <div class="font-bold text-gray-800 text-base" title="Tồn kho khả dụng hiện tại">@nfmt($product->inventory->quantity ?? 0)</div>
                            @if($latest)
                                <div class="text-[9px] text-emerald-600 font-black uppercase italic" title="Số lượng nhập kho gần nhất">
                                    Mới nhập: +@nfmt($latest->quantity)
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$latest)
                                <span class="text-[10px] text-gray-300 italic">Chưa phát phát sinh nhập</span>
                            @elseif($latest->qa_inspection_status === 'pending')
                                <button wire:click="startInspecting({{ $product->id }})" class="px-2.5 py-1 bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 rounded-lg text-xs font-bold border border-red-100 transition-colors">
                                    <i class="fa-solid fa-clock mr-1"></i> Chưa kiểm tra
                                </button>
                            @else
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-lg text-xs font-bold border border-yellow-200 animate-pulse">
                                    <i class="fa-solid fa-magnifying-glass mr-1"></i> Đang kiểm tra
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($latest)
                                @if($latest->qa_status === 'pending')
                                    <span class="px-2.5 py-1 bg-gray-100 text-gray-500 rounded-lg text-[10px] font-bold uppercase">Chưa duyệt</span>
                                @elseif($latest->qa_status === 'approved')
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-600 rounded-lg text-[10px] font-bold uppercase"><i class="fa-solid fa-check mr-1"></i> Đạt</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-100 text-rose-600 rounded-lg text-[10px] font-bold uppercase"><i class="fa-solid fa-xmark mr-1"></i> Không đạt</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs text-gray-500 italic truncate max-w-[150px]" title="{{ $latest?->qa_note }}">{{ $latest?->qa_note ?: '---' }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button wire:click="openApprovalModal({{ $product->id }})" class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-emerald-600 hover:text-white transition-colors">
                                Thẩm định
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            Chưa có dữ liệu nguyên vật liệu nào trong danh mục.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Modal Thẩm Định --}}
    @if($showApprovalModal)
    <div class="fixed inset-0 z-[1002] overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeApprovalModal"></div>
            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full border border-gray-100 z-10">
                <div class="bg-emerald-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-black uppercase tracking-tighter">
                        <i class="fa-solid fa-shield-check mr-2"></i> THẨM ĐỊNH CHẤT LƯỢNG
                    </h3>
                    <button wire:click="closeApprovalModal" class="text-white/80 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase mb-2">Kết quả thẩm định *</label>
                        <select wire:model="qa_status" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all font-bold text-gray-800">
                            <option value="pending">Chưa duyệt (Đang xem xét)</option>
                            <option value="approved">Đạt tiêu chuẩn (Đã duyệt)</option>
                            <option value="rejected">Không đạt (Từ chối)</option>
                        </select>
                        @error('qa_status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase mb-2">Ghi chú / Lưu ý chất lượng</label>
                        <textarea wire:model="qa_note" rows="3" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all" placeholder="Nhập kết quả test, thông số hoặc lý do không đạt..."></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button wire:click="closeApprovalModal" class="px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 font-bold transition-all">
                        Hủy bỏ
                    </button>
                    <button wire:click="saveApproval" class="px-6 py-2 bg-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-600/20 hover:bg-emerald-700 font-bold transition-all">
                        Lưu kết quả
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modal Thêm Mới NVL --}}
    @if($showCreateModal)
    <div class="fixed inset-0 z-[1002] overflow-y-auto" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeCreateModal"></div>
            <div class="inline-block align-middle bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-md w-full border border-gray-100 z-10">
                <div class="bg-primary px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="text-lg font-black uppercase tracking-tighter">
                        <i class="fa-solid fa-plus-circle mr-2"></i> THÊM MỚI NGUYÊN VẬT LIỆU
                    </h3>
                    <button wire:click="closeCreateModal" class="text-white/80 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="px-6 py-6 space-y-4">
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase mb-2">Tên nguyên vật liệu *</label>
                        <input wire:model="newName" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-gray-800" placeholder="Nhập tên NVL mới...">
                        @error('newName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase mb-2">Đơn vị tính *</label>
                        <input wire:model="newUnit" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all font-bold text-gray-800" placeholder="Chai, Cái, Kg, Mét...">
                        @error('newUnit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <p class="mt-4 text-[11px] text-gray-400 italic">* Các thông tin Mã NVL, Giá... sẽ được hệ thống khởi tạo mặc định và có thể cập nhật sau tại Danh mục sản phẩm.</p>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button wire:click="closeCreateModal" class="px-5 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-100 font-bold transition-all text-xs">
                        HỦY
                    </button>
                    <button wire:click="saveMaterial" class="px-6 py-2 bg-primary text-white rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-600 font-bold transition-all text-xs">
                        LƯU NGUYÊN VẬT LIỆU
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
