<div class="max-w-[1200px] mx-auto pb-10">
    <style>
        @media print {
            .no-print, button, input[type="search"], .modal-overlay, .side-nav, .top-nav { display: none !important; }
            body, .main-content { background: white !important; padding: 0 !important; margin: 0 !important; }
            .print-only { display: block !important; }
            .shadow-md, .rounded-xl, .rounded-2xl { box-shadow: none !important; border: 1px solid #eee !important; }
            
            /* Chỉ in những dòng được tick */
            tr:has(input[type="checkbox"]):not(:has(input[type="checkbox"]:checked)) {
                display: none !important;
            }
            
            .voucher-container { padding: 40px !important; }
            .voucher-header { border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
            .voucher-title { font-size: 24px; font-weight: bold; text-align: center; text-transform: uppercase; }
            
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #000; padding: 8px; text-align: left; font-size: 12px; }
            th { background-color: #f2f2f2 !important; -webkit-print-color-adjust: exact; }
            
            .signature-section { margin-top: 50px; display: flex; justify-content: space-between; }
            .signature-box { text-align: center; width: 30%; }
            .signature-label { font-weight: bold; margin-bottom: 60px; display: block; }
        }
        .print-only { display: none; }
    </style>

    {{-- HEADER THANH ĐIỀU HƯỚNG --}}
    <div class="flex items-center justify-between mb-4 no-print">
        <div class="flex items-center">
            <a wire:navigate href="{{ route('warehouse.index') }}" class="group mr-3 p-1.5 bg-white rounded-lg shadow-sm border border-gray-100 hover:bg-gray-50 transition-all">
                <i class="fa-solid fa-arrow-left text-gray-400 group-hover:text-gray-600 text-sm"></i>
            </a>
            <div>
                <h2 class="text-xl font-black text-gray-900 tracking-tighter uppercase flex items-center">
                    @if($type === 'import')
                        <i class="fa-solid fa-file-import text-blue-600 mr-2 text-lg"></i> 
                        Lập Phiếu Nhập Kho {{ $warehouse_code === 'FINISHED_GOODS' ? 'Thành Phẩm' : ($warehouse_code === 'RAW_MAT' ? 'Nguyên Liệu' : 'Vật Tư') }}
                    @else
                        <i class="fa-solid fa-file-export text-orange-500 mr-2 text-lg"></i> 
                        Lập Phiếu Xuất Kho {{ $warehouse_code === 'FINISHED_GOODS' ? 'Thành Phẩm' : ($warehouse_code === 'RAW_MAT' ? 'Nguyên Liệu' : 'Vật Tư') }}
                    @endif
                </h2>
            </div>
        </div>
        <div class="flex items-center space-x-2 no-print">
             <button type="button" wire:click="printVoucher" class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 px-3 py-1.5 rounded-lg shadow-sm transition-all flex items-center text-[10px] font-black uppercase tracking-widest mr-2">
                <i class="fa-solid fa-print mr-2 text-blue-500"></i> In phiếu tạm
             </button>
             <div class="bg-blue-50/50 text-blue-900 px-3 py-1.5 rounded-lg border border-blue-100 shadow-sm flex flex-col items-end">
                <span class="text-[8px] font-black uppercase tracking-widest opacity-50">Số phiếu</span>
                <span class="text-sm font-black font-mono tracking-tighter">{{ $voucher_no }}</span>
             </div>
        </div>
    </div>

    <div class="space-y-4 voucher-container text-gray-800">
        {{-- BẢN IN: THÔNG TIN HEADER (Chỉ hiện khi in) --}}
        <div class="print-only">
            <div class="voucher-header flex justify-between items-start">
                <div>
                  <h1 class="text-xl font-bold uppercase">CÔNG TY TNHH CRM TECH</h1>
                  <p class="text-xs">Địa chỉ: 123 Đường ABC, Quận XYZ, TP.HCM</p>
                  <p class="text-xs">SĐT: 0123.456.789</p>
                </div>
                <div class="text-right">
                  <p class="text-sm font-bold">Số phiếu: {{ $voucher_no }}</p>
                  <p class="text-xs">Ngày lập: {{ \Carbon\Carbon::parse($transaction_date)->format('d/m/Y') }}</p>
                </div>
            </div>
            <h2 class="voucher-title mb-6">
                 PHIẾU {{ $type === 'import' ? 'NHẬP' : 'XUẤT' }} KHO {{ $warehouse_code === 'FINISHED_GOODS' ? 'THÀNH PHẨM' : ($warehouse_code === 'RAW_MAT' ? 'NGUYÊN LIỆU' : 'VẬT TƯ') }}
            </h2>
            <div class="grid grid-cols-2 gap-4 text-xs mb-6">
                 <p><strong>Đối tượng:</strong> {{ $partner_name ?: '---' }}</p>
                 <p><strong>Số điện thoại:</strong> {{ $partner_phone ?: '---' }}</p>
                 <p class="col-span-2"><strong>Địa chỉ:</strong> {{ $partner_address ?: '---' }}</p>
                 <p class="col-span-2"><strong>Ghi chú:</strong> {{ $note ?: '---' }}</p>
            </div>
        </div>

        {{-- PHẦN 1: THÔNG TIN CHUNG (Form view) --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden no-print">
            <div class="bg-blue-50/30 px-5 py-2.5 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-blue-600">
                    <i class="fa-solid fa-circle-info mr-1.5 "></i> Thông tin chung
                </h3>
            </div>
            
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    {{-- Dòng 1 --}}
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Đối tượng (NCC/KH)</label>
                        <div class="flex space-x-2">
                            <div class="relative flex-1">
                                <input type="text" 
                                    wire:model.live.debounce.300ms="partnerSearch" 
                                    @focus="$wire.set('showPartnerResults', true)"
                                    class="w-full pl-9 pr-4 py-2 rounded-xl border-gray-100 bg-gray-50/50 focus:bg-white focus:ring-1 focus:ring-primary focus:border-primary font-bold text-gray-900 text-xs shadow-sm"
                                    placeholder="Gõ tên hoặc mã...">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa-solid fa-user-tie text-blue-400 text-xs"></i>
                                </div>
                                
                                {{-- Quick Search Results --}}
                                @if($showPartnerResults && strlen($partnerSearch) >= 2)
                                    <div class="absolute z-[100] mt-1 w-full bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                                        @forelse($partners as $p)
                                            <button type="button" wire:click="selectPartner({{ $p->id }})" class="w-full text-left px-4 py-2.5 hover:bg-blue-50 border-b border-gray-50 last:border-0 group">
                                                <p class="font-bold text-gray-900 text-xs group-hover:text-blue-700">[{{ $p->customer_code }}] {{ $p->name }}</p>
                                            </button>
                                        @empty
                                            <div class="px-4 py-4 text-center text-gray-400 text-xs italic">Không thấy...</div>
                                        @endforelse
                                    </div>
                                @endif
                            </div>
                            <button type="button" wire:click="togglePartnerModal" class="px-3 bg-white hover:bg-gray-50 text-blue-600 rounded-xl transition-all border border-blue-100 shadow-sm" title="Mở danh sách chọn lọc">
                                <i class="fa-solid fa-list-ul text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">SĐT (Auto)</label>
                        <input type="text" wire:model="partner_phone" readonly class="w-full px-3 py-2 rounded-xl border-gray-50 bg-gray-50 text-gray-400 font-bold text-xs cursor-not-allowed" placeholder="...">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Ngày lập</label>
                        <input type="date" wire:model="transaction_date" class="w-full px-3 py-2 rounded-xl border-gray-100 focus:ring-1 focus:ring-primary focus:border-primary font-bold text-gray-900 text-xs shadow-sm">
                    </div>

                    {{-- Dòng 2 --}}
                    <div class="md:col-span-2">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Địa chỉ (Auto)</label>
                        <input type="text" wire:model="partner_address" readonly class="w-full px-3 py-2 rounded-xl border-gray-50 bg-gray-50 text-gray-400 font-bold text-xs cursor-not-allowed italic" placeholder="...">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Hóa đơn</label>
                        <input type="text" wire:model="invoice_number" class="w-full px-3 py-2 rounded-xl border-gray-100 focus:ring-1 focus:ring-primary focus:border-primary font-bold text-gray-900 text-xs shadow-sm" placeholder="...">
                    </div>

                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-1">Diễn giải</label>
                        <input type="text" wire:model="note" class="w-full px-3 py-2 rounded-xl border-gray-100 focus:ring-1 focus:ring-primary focus:border-primary font-bold text-gray-900 text-xs shadow-sm" placeholder="Ghi chú...">
                    </div>
                </div>
            </div>
        </div>

        {{-- PHẦN 2: DANH SÁCH HÀNG HÓA (Detail) --}}
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="bg-gray-50/50 px-5 py-2.5 border-b border-gray-100 flex items-center justify-between no-print">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                    <i class="fa-solid fa-list-check mr-1.5 text-blue-500"></i> Hàng hóa chi tiết
                </h3>
                <button type="button" wire:click="addRow" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg shadow-sm transition-all text-[9px] font-black uppercase tracking-widest">
                    <i class="fa-solid fa-plus mr-1"></i> Thêm dòng
                </button>
            </div>

            <div class="">
                <table class="w-full border-collapse">
                    <thead class="bg-amber-50/50 border-b border-amber-100">
                        <tr>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-center w-12 no-print">
                                <i class="fa-solid fa-print"></i>
                            </th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-left">
                                @if($warehouse_code === 'FINISHED_GOODS') SẢN PHẨM @elseif($warehouse_code === 'RAW_MAT') NGUYÊN VẬT LIỆU @else VẬT TƯ @endif
                            </th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-left w-28">Hãng SX</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-center w-28">Số lô</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-center w-28">Vị trí</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-center w-32">Hạn dùng</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-center w-20">ĐVT</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-center w-24">Số lượng</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-right w-32">Đơn giá</th>
                            <th class="px-5 py-2.5 text-[9px] font-black uppercase tracking-widest text-amber-600/70 text-right w-40">Thành tiền</th>
                            <th class="px-5 py-2.5 w-12 no-print"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($items as $index => $item)
                        <tr class="hover:bg-gray-50/20 transition-colors" wire:key="row-{{ $index }}">
                            <td class="px-5 py-1.5 text-center no-print">
                                <input type="checkbox" wire:model.live="items.{{ $index }}.checked" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-0">
                            </td>
                            <td class="px-5 py-1.5">
                                <div class="relative no-print" x-data="{ open: false }">
                                    <div class="flex flex-col">
                                        <input type="text" 
                                            wire:model.live.debounce.300ms="items.{{ $index }}.search" 
                                            @focus="open = true"
                                            @click.away="open = false"
                                            class="w-full bg-transparent border-0 focus:ring-0 p-0 font-bold text-gray-900 text-xs placeholder:text-gray-300 italic"
                                            placeholder="Gõ mã/tên SP...">
                                        
                                        @if(isset($items[$index]['product_id']) && $items[$index]['product_id'])
                                            <span class="text-[8px] text-blue-500 font-black uppercase tracking-tighter mt-0.5">{{ $items[$index]['product_name'] }}</span>
                                        @endif
                                    </div>

                                    {{-- Product Search Popup --}}
                                    <div x-show="open && $wire.items[{{ $index }}].search.length >= 2" class="absolute z-[110] mt-1 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 overflow-hidden">
                                        <div class="max-h-60 overflow-y-auto">
                                            @php
                                                $searchText = $items[$index]['search'];
                                                $filteredProducts = $allProducts->filter(function($p) use ($searchText) {
                                                    return str_contains(strtolower($p->name), strtolower($searchText)) || str_contains(strtolower($p->code), strtolower($searchText));
                                                })->take(8);
                                            @endphp
                                            @forelse($filteredProducts as $p)
                                                <button type="button" wire:click="selectProduct({{ $index }}, {{ $p->id }})" @click="open = false" class="w-full text-left px-4 py-2 hover:bg-blue-50 border-b border-gray-50 last:border-0 flex justify-between items-center group">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-gray-900 text-[10px]">[{{ $p->code }}] {{ $p->name }}</span>
                                                        <span class="text-[8px] text-gray-400 font-bold uppercase">{{ $p->unit }}</span>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-[9px] text-emerald-600 font-black">@nfmt($p->inventory?->quantity ?? 0)</div>
                                                        <div class="text-[7px] text-gray-300 uppercase font-bold">Tồn kho</div>
                                                    </div>
                                                </button>
                                            @empty
                                                <div class="px-4 py-4 text-center text-gray-400 text-[10px] italic">Không thấy...</div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                {{-- HIỂN THỊ KHI IN --}}
                                <div class="print-only text-xs font-bold">{{ $item['product_name'] }}</div>
                            </td>
                            {{-- Hãng SX --}}
                            <td class="px-3 py-1.5">
                                <input type="text" wire:model="items.{{ $index }}.manufacturer_name" class="w-full bg-transparent border-0 focus:ring-1 focus:ring-blue-100 py-0.5 text-left font-medium text-gray-600 text-[10px] no-print" placeholder="...">
                                <div class="print-only text-[10px]">{{ $item['manufacturer_name'] ?? '---' }}</div>
                            </td>
                            {{-- Số lô --}}
                            <td class="px-3 py-1.5">
                                <input type="text" wire:model="items.{{ $index }}.batch_number" class="w-full bg-gray-50 rounded-md border-0 focus:ring-1 focus:ring-blue-100 py-0.5 text-center font-bold text-gray-900 text-[10px] no-print" placeholder="Lot...">
                                <div class="print-only text-center text-[10px] font-bold">{{ $item['batch_number'] ?? '---' }}</div>
                            </td>
                            {{-- Vị trí --}}
                            <td class="px-3 py-1.5">
                                <input type="text" wire:model="items.{{ $index }}.location" 
                                    placeholder="{{ $item['location_placeholder'] ?: '' }}"
                                    class="w-full bg-transparent border-0 focus:ring-1 focus:ring-blue-100 py-0.5 text-center font-medium text-gray-600 text-[10px] no-print placeholder:text-gray-300 italic">
                                <div class="print-only text-center text-[10px]">{{ $item['location'] ?: ($item['location_placeholder'] ?: '---') }}</div>
                            </td>
                            {{-- Hạn dùng --}}
                            <td class="px-3 py-1.5">
                                <input type="date" wire:model="items.{{ $index }}.expiry_date" class="w-full bg-transparent border-0 focus:ring-1 focus:ring-blue-100 py-0.5 text-center font-medium text-gray-600 text-[10px] no-print">
                                <div class="print-only text-center text-[10px]">{{ ($item['expiry_date'] ?? null) ? \Carbon\Carbon::parse($item['expiry_date'])->format('d/m/Y') : '---' }}</div>
                            </td>
                            <td class="px-5 py-1.5 text-center">
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-tighter">{{ $item['unit'] ?? '---' }}</span>
                                <div class="print-only text-[10px] font-bold">{{ $item['unit'] ?? '---' }}</div>
                            </td>
                            <td class="px-5 py-1.5">
                                <input type="text" 
                                    wire:model.blur="items.{{ $index }}.quantity"
                                    @keydown.enter.prevent="$event.target.closest('tr').querySelector('.price-input').focus()"
                                    class="w-full bg-amber-50 rounded-lg border-0 focus:ring-1 focus:ring-amber-500 py-0.5 text-center font-black text-amber-700 text-xs no-print" 
                                    placeholder="0">
                                <div class="print-only text-center text-xs font-bold">@nfmt($item['quantity'] ?? 0)</div>
                            </td>
                            <td class="px-5 py-1.5">
                                <input type="text" 
                                    wire:model.blur="items.{{ $index }}.price"
                                    class="price-input w-full bg-transparent border-0 focus:ring-0 py-0.5 text-right font-black text-gray-900 text-xs placeholder:text-gray-300 no-print" 
                                    placeholder="0">
                                <div class="print-only text-right text-xs font-bold">@nfmt($item['price'] ?? 0)</div>
                            </td>
                            <td class="px-5 py-1.5 text-right">
                                <span class="text-xs font-black text-blue-700 no-print">@nfmt($item['amount'] ?? 0)</span>
                                <div class="print-only text-right text-xs font-bold">@nfmt($item['amount'] ?? 0)</div>
                            </td>
                            <td class="px-5 py-1.5 text-right no-print">
                                <button type="button" wire:click="removeRow({{ $index }})" class="text-gray-300 hover:text-red-500 transition-colors p-1">
                                    <i class="fa-solid fa-xmark text-xs"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- FOOTER TỔNG CỘNG --}}
            <div class="bg-amber-50 px-6 py-4 flex items-center justify-between border-t border-amber-100">
                <div class="hidden md:block no-print">
                    <p class="text-[8px] font-black uppercase tracking-widest text-amber-400">Xác nhận dữ liệu trước khi lưu phiếu</p>
                </div>
                
                <div class="flex items-center space-x-4">
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-600">Tổng cộng VNĐ:</span>
                    <span class="text-3xl font-black text-blue-700 tracking-tighter">@nfmt($total_amount)</span>
                </div>
            </div>
        </div>

        {{-- BẢN IN: PHẦN CHỮ KÝ (Chỉ hiện khi in) --}}
        <div class="print-only signature-section">
             <div class="signature-box">
                 <span class="signature-label">Người lập phiếu</span>
                 <p class="text-[10px] italic">(Ký, ghi rõ họ tên)</p>
             </div>
             <div class="signature-box">
                 <span class="signature-label">Thủ kho</span>
                 <p class="text-[10px] italic">(Ký, ghi rõ họ tên)</p>
             </div>
             <div class="signature-box">
                 <span class="signature-label">Người nhận hàng</span>
                 <p class="text-[10px] italic">(Ký, ghi rõ họ tên)</p>
             </div>
        </div>

        {{-- NÚT THAO TÁC (NO PRINT) --}}
        <div class="flex items-center justify-end space-x-3 mt-4 no-print">
            <a wire:navigate href="{{ route('warehouse.index') }}" class="px-6 py-2 rounded-xl border border-gray-200 bg-white text-gray-400 font-black uppercase tracking-widest text-[9px] hover:bg-gray-50 transition-all">
                Hủy phiếu
            </a>
            
            <button type="button" wire:click="save" wire:loading.attr="disabled" class="px-10 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-black uppercase tracking-widest text-[10px] shadow-lg shadow-blue-200 transition-all flex items-center min-w-[200px] justify-center">
                <span wire:loading.remove><i class="fa-solid fa-cloud-arrow-up mr-2"></i> LƯU CHỨNG TỪ</span>
                <span wire:loading><i class="fa-solid fa-circle-notch animate-spin mr-2"></i> ĐANG LƯU...</span>
            </button>
        </div>
    </div>

    {{-- MODAL CHỌN ĐỐI TƯỢNG (List chọn lọc) --}}
    @if($showPartnerModal)
    <div class="fixed inset-0 z-[1000] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-gray-950 bg-opacity-75 transition-opacity pointer-events-none" aria-hidden="true" wire:click="togglePartnerModal"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full p-0">
                <div class="bg-gray-50 px-8 py-5 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tighter uppercase">Danh sách {{ $type === 'import' ? 'Nhà cung cấp' : 'Khách hàng' }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Chọn một đối tượng để điền thông tin phiếu</p>
                    </div>
                    <button wire:click="togglePartnerModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="p-8">
                    {{-- Search in modal --}}
                    <div class="relative mb-6">
                        <input type="text" wire:model.live.debounce.300ms="partnerSearch" class="w-full pl-10 pr-4 py-3 rounded-2xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-primary focus:border-primary font-bold text-gray-900 text-sm shadow-inner transition-all" placeholder="Tìm theo tên, mã hoặc số điện thoại...">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fa-solid fa-magnifying-glass text-gray-400"></i>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-gray-500 font-black uppercase tracking-widest text-[10px]">
                                <tr>
                                    <th class="px-6 py-4 text-left">Mã khách</th>
                                    <th class="px-6 py-4 text-left">Tên đơn vị</th>
                                    <th class="px-6 py-4 text-left">Số điện thoại</th>
                                    <th class="px-6 py-4 text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @php
                                    $allPartners = \App\Models\Customer::where('name', 'like', '%' . $this->partnerSearch . '%')
                                        ->orWhere('customer_code', 'like', '%' . $this->partnerSearch . '%')
                                        ->orWhere('phone', 'like', '%' . $this->partnerSearch . '%')
                                        ->get();
                                @endphp
                                @forelse($allPartners as $p)
                                <tr class="hover:bg-blue-50/30 transition-colors">
                                    <td class="px-6 py-4 font-mono font-bold text-primary">{{ $p->customer_code }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-black text-gray-900">{{ $p->name }}</div>
                                        <div class="text-[10px] text-gray-400"><i class="fa-solid fa-location-dot mr-1"></i> {{ $p->address }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 font-bold">{{ $p->phone ?: '---' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <button wire:click="selectPartner({{ $p->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-xl shadow-md shadow-blue-100 transition-all text-[10px] font-black uppercase tracking-widest">
                                            Chọn
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">Không tìm thấy đối tác nào phù hợp...</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
