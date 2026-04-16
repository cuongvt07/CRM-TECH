<div class="p-6 bg-slate-50 min-h-screen">
    <div class="mb-6">
        <h1 class="text-2xl font-black text-slate-800 uppercase tracking-tight">Báo cáo QA/QC</h1>
        <p class="text-slate-500 text-sm">Tổng hợp kết quả kiểm soát chất lượng và hiệu suất sản xuất</p>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tổng lệnh hoàn tất</div>
            <div class="text-3xl font-black text-slate-800">{{ $stats['total'] }}</div>
            <div class="mt-2 text-[10px] text-slate-500 font-bold">Dựa trên dữ liệu thực tế</div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Số lượng Đạt (Pass)</div>
            <div class="text-3xl font-black text-emerald-600">{{ $stats['pass'] }}</div>
            <div class="mt-2 flex items-center text-[10px] text-emerald-600 font-bold italic">
                <i class="fa-solid fa-circle-check mr-1 text-[8px]"></i> Hệ thống tự động ghi nhận
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <div class="text-[10px] font-black text-red-500 uppercase tracking-widest mb-1">Số lượng Lỗi (Fail)</div>
            <div class="text-3xl font-black text-red-600">{{ $stats['fail'] }}</div>
            <div class="mt-2 text-[10px] text-red-400 font-bold uppercase">Cần phân tích nguyên nhân</div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-500 bg-emerald-50/20">
            <div class="text-[10px] font-black text-emerald-700 uppercase tracking-widest mb-1">Tỷ lệ đạt chuẩn</div>
            <div class="text-3xl font-black text-emerald-800">{{ $stats['pass_rate'] }}%</div>
            <div class="mt-2 w-full bg-emerald-200 h-1 rounded-full overflow-hidden">
                <div class="bg-emerald-600 h-full" style="width: {{ $stats['pass_rate'] }}%"></div>
            </div>
        </div>
    </div>

    <!-- Detailed Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="font-bold text-slate-800 text-sm italic uppercase tracking-tighter">Chi tiết chất lượng theo lệnh sản xuất</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-[10px] uppercase font-black">
                        <th class="px-6 py-4">Lệnh / Sản phẩm</th>
                        <th class="px-6 py-4">Số lượng</th>
                        <th class="px-6 py-4">Hoàn thành Checklist</th>
                        <th class="px-6 py-4">Kết luận QA</th>
                        <th class="px-6 py-4">Thời gian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($reports as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-slate-300">#{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</span>
                                    <span class="font-bold text-slate-700">{{ $order->product?->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-black text-slate-800">@nfmt($order->quantity)</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $done = $order->qaChecklists->where('is_completed', true)->count();
                                    $total = $order->qaChecklists->count();
                                    $p = $total > 0 ? round(($done / $total) * 100) : 0;
                                @endphp
                                <div class="flex flex-col space-y-1">
                                    <div class="flex justify-between items-center text-[9px] font-bold text-slate-500">
                                        <span>{{ $done }}/{{ $total }} bước</span>
                                        <span>{{ $p }}%</span>
                                    </div>
                                    <div class="w-24 bg-slate-100 h-1 rounded-full overflow-hidden">
                                        <div class="bg-{{ $p == 100 ? 'emerald' : 'blue' }}-500 h-full" style="width: {{ $p }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php $res = $order->qcReports->first()?->result ?? 'N/A'; @endphp
                                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $res === 'pass' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : ($res === 'fail' ? 'bg-red-50 text-red-600 border-red-100' : 'bg-slate-100 text-slate-500 border-slate-200') }}">
                                    {{ $res === 'pass' ? 'ĐẠT CHUẨN' : ($res === 'fail' ? 'KHÔNG ĐẠT' : 'CHƯA NGHIỆM THU') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs text-slate-400 italic">
                                {{ $order->actual_end_date ? $order->actual_end_date->format('d/m/Y H:i') : '--' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 italic text-sm">
                                Chưa có dữ liệu báo cáo cho các lệnh đã hoàn thành
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $reports->links() }}
        </div>
    </div>
</div>
