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
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-success border-gray-100">
            <h3 class="text-sm font-medium text-gray-500">Doanh thu hôm nay</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">12,500,000 đ</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-primary border-gray-100">
            <h3 class="text-sm font-medium text-gray-500">Đơn hàng mới</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">15</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-warning border-gray-100">
            <h3 class="text-sm font-medium text-gray-500">Đang sản xuất</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">8</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm p-6 border border-l-4 border-l-error border-gray-100">
            <h3 class="text-sm font-medium text-gray-500">Tồn kho cảnh báo</h3>
            <p class="text-2xl font-bold text-gray-900 mt-2">3</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue Line Chart -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 flex flex-col w-full h-80" 
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
                            }
                        }
                    });
                }
             }"
             x-init="initChart()">
            
            <h3 class="font-bold text-gray-800 mb-4">Biểu đồ Doanh thu (Năm nay)</h3>
            <div class="relative flex-1 w-full min-h-0">
                <canvas x-ref="revenueCanvas"></canvas>
            </div>
        </div>

        <!-- Pie Chart Placeholder -->
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-100 h-80 flex items-center justify-center">
            <p class="text-gray-400">Tỷ lệ trạng thái đơn hàng (Pie Chart)</p>
        </div>
    </div>
    
    <!-- Load Chart.js script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</div>
