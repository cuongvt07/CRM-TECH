<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 uppercase tracking-tight">Phân tích Sản xuất</h2>
            <p class="text-sm text-gray-500 mt-1">Báo cáo tổng hợp năng suất và chất lượng sản phẩm.</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-xs font-bold uppercase hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-download mr-2"></i> Xuất PDF
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase italic">Tổng sản lượng (Năm)</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-3xl font-black text-primary">@nfmt(array_sum($monthlyProduced))</span>
                <span class="text-xs text-green-500 font-bold mb-1"><i class="fa-solid fa-arrow-up mr-1"></i> 12%</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase italic">Tỷ lệ QC Pass</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-3xl font-black text-green-600">
                    {{ array_sum($qcStats) > 0 ? round(($qcStats[0] / array_sum($qcStats)) * 100, 1) : 0 }}%
                </span>
                <span class="text-xs text-gray-400 font-bold mb-1">Mục tiêu: 98%</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase italic">Đang sản xuất</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-3xl font-black text-orange-500">{{ $statusStats[1] }}</span>
                <span class="text-xs text-orange-400 font-bold mb-1 italic">Đang chạy máy</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <span class="text-xs font-bold text-gray-400 uppercase italic">Lệnh chờ QC</span>
            <div class="flex items-end justify-between mt-2">
                <span class="text-3xl font-black text-purple-600">{{ $statusStats[2] }}</span>
                <span class="text-xs text-purple-400 font-bold mb-1 italic">Cần kiểm tra</span>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Production Chart -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100" 
             x-data="{
                init() {
                    new Chart(this.$refs.productionChart, {
                        type: 'line',
                        data: {
                            labels: ['Th1', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'Th8', 'Th9', 'Th10', 'Th11', 'Th12'],
                            datasets: [{
                                label: 'Sản lượng',
                                data: @js($monthlyProduced),
                                borderColor: '#1677FF',
                                backgroundColor: 'rgba(22, 119, 255, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointBackgroundColor: '#fff',
                                pointBorderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });
                }
             }">
            <h3 class="font-bold text-gray-800 mb-6 uppercase tracking-wider text-sm">Sản lượng hoàn thành theo tháng</h3>
            <div class="h-64">
                <canvas x-ref="productionChart"></canvas>
            </div>
        </div>

        <!-- Status & QC Split -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status Distribution -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100"
                 x-data="{
                    init() {
                        new Chart(this.$refs.statusChart, {
                            type: 'doughnut',
                            data: {
                                labels: ['Chờ', 'Làm', 'QC', 'Xong'],
                                datasets: [{
                                    data: @js($statusStats),
                                    backgroundColor: ['#94a3b8', '#f97316', '#a855f7', '#22c55e'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '70%',
                                plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
                            }
                        });
                    }
                 }">
                <h3 class="font-bold text-gray-800 mb-6 uppercase tracking-wider text-sm text-center">Trạng thái lệnh SX</h3>
                <div class="h-48">
                    <canvas x-ref="statusChart"></canvas>
                </div>
            </div>

            <!-- QC Performance -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100"
                 x-data="{
                    init() {
                        new Chart(this.$refs.qcChart, {
                            type: 'bar',
                            data: {
                                labels: ['Pass', 'Fail'],
                                datasets: [{
                                    data: @js($qcStats),
                                    backgroundColor: ['#22c55e', '#ef4444'],
                                    borderRadius: 8
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { display: false } },
                                scales: {
                                    y: { beginAtZero: true, grid: { display: false } },
                                    x: { grid: { display: false } }
                                }
                            }
                        });
                    }
                 }">
                <h3 class="font-bold text-gray-800 mb-6 uppercase tracking-wider text-sm text-center">Kết quả QC</h3>
                <div class="h-48">
                    <canvas x-ref="qcChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
