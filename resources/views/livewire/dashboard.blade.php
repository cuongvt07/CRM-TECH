<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Tổng quan</h1>
        <nav class="text-sm font-medium text-gray-500">
            <ol class="list-none p-0 inline-flex">
                <li class="flex items-center">
                    <a href="#" class="hover:text-primary transition-colors">Trang chủ</a>
                    <span class="mx-2">/</span>
                </li>
                <li class="flex items-center text-gray-700">Dashboard</li>
            </ol>
        </nav>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-success border-gray-100 group hover:shadow-md transition-shadow">
            <h3 class="text-sm font-medium text-gray-500">Doanh thu hôm nay</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($todayRevenue, 0, ',', '.') }} đ</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-primary border-gray-100 group hover:shadow-md transition-shadow">
            <h3 class="text-sm font-medium text-gray-500">Đơn hàng mới hôm nay</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($newOrdersCount) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-warning border-gray-100 group hover:shadow-md transition-shadow">
            <h3 class="text-sm font-medium text-gray-500">Đang sản xuất (Thiếu kho)</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">{{ number_format($inProductionCount) }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-error border-gray-100 group hover:shadow-md transition-shadow">
            <h3 class="text-sm font-medium text-gray-500">Mặt hàng cảnh báo tồn</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2 {{ $lowStockCount > 0 ? 'text-red-600 animate-pulse' : '' }}">{{ number_format($lowStockCount) }}</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Revenue Line Chart -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex flex-col w-full h-96" 
             x-data="{
                initChart() {
                    new Chart(this.$refs.revenueCanvas, {
                        type: 'line',
                        data: {
                            labels: @js($revenueLabels),
                            datasets: [{
                                label: 'Doanh thu (VNĐ)',
                                data: @js($revenueData),
                                borderColor: '#1677FF',
                                backgroundColor: 'rgba(22, 119, 255, 0.1)',
                                tension: 0.3,
                                fill: true,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'top' }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString() + ' đ';
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
             }"
             x-init="initChart()">
            
            <h3 class="font-bold text-gray-800 mb-4">Biểu đồ Doanh thu (Năm {{ date('Y') }})</h3>
            <div class="relative flex-1 w-full min-h-0">
                <canvas x-ref="revenueCanvas"></canvas>
            </div>
        </div>

        <!-- Status Pie Chart -->
        <div class="lg:col-span-1 bg-white rounded-lg shadow-sm p-6 border border-gray-100 h-96 flex flex-col"
             x-data="{
                initPieChart() {
                    new Chart(this.$refs.statusCanvas, {
                        type: 'doughnut',
                        data: {
                            labels: @js($statusLabels),
                            datasets: [{
                                data: @js($statusData),
                                backgroundColor: [
                                    '#FFCD56', '#36A2EB', '#FF9F40', '#4BC0C0', '#9966FF', '#C9CBCF', '#FF6384'
                                ]
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { position: 'bottom' }
                            }
                        }
                    });
                }
             }"
             x-init="initPieChart()">
            <h3 class="font-bold text-gray-800 mb-4">Tỷ lệ trạng thái đơn hàng</h3>
            <div class="relative flex-1 w-full min-h-0">
                <canvas x-ref="statusCanvas"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section: Leaderboard & Recent (Optional) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1 bg-white rounded-lg shadow-sm p-6 border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                <i class="fa-solid fa-trophy text-yellow-500 mr-2"></i> Bảng xếp hạng doanh thu
            </h3>
            <div class="space-y-4">
                @forelse($topStaff as $index => $staff)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 relative">
                                <img class="h-10 w-10 rounded-full border border-gray-200" 
                                     src="{{ $staff->avatar ? asset('storage/'.$staff->avatar) : 'https://ui-avatars.com/api/?name='.urlencode($staff->name) }}" 
                                     alt="">
                                <span class="absolute -top-1 -left-1 w-5 h-5 rounded-full bg-primary text-white text-[10px] flex items-center justify-center font-bold">
                                    {{ $index + 1 }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $staff->name }}</p>
                                <p class="text-xs text-gray-500">Doanh số tháng</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-primary">{{ number_format($staff->total_revenue, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-gray-400">VNĐ</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">Chưa có dữ liệu doanh thu</p>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex flex-col items-center justify-center text-center space-y-3">
             <i class="fa-solid fa-chart-line text-4xl text-gray-200"></i>
             <p class="text-gray-400 font-medium">Báo cáo chi tiết & Dự báo xu hướng</p>
             <button class="text-primary font-semibold text-sm hover:underline">Xem báo cáo đầy đủ →</button>
        </div>
    </div>
    
    <!-- Load Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
