<div>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Báo cáo Kho & Phân tích</h2>
            <p class="text-gray-500 mt-1">Theo dõi xu hướng xuất hàng, doanh thu và biến động vật tư.</p>
        </div>
        <div class="flex items-center space-x-2 no-print">
            <button onclick="window.print()" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors flex items-center">
                <i class="fa-solid fa-print mr-2"></i> Xuất Báo cáo (PDF)
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8 mb-8">
        {{-- CHART 1: XU HƯỚNG 12 THÁNG --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fa-solid fa-chart-line text-blue-500 mr-2"></i> Xu hướng Xuất hàng & Doanh thu (12 tháng)
                </h3>
            </div>
            <div id="shipmentTrendChart" style="min-height: 400px;"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-1 gap-8">
        {{-- CHART 2: MUA & BÁN TOP 20 --}}
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center">
                    <i class="fa-solid fa-chart-bar text-orange-500 mr-2"></i> Top 20 Sản phẩm: So sánh Nhập (Mua) vs Xuất (Bán) - Tháng {{ now()->format('m/Y') }}
                </h3>
            </div>
            <div id="topProductsChart" style="min-height: 500px;"></div>
        </div>
    </div>

    {{-- ApexCharts CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:navigated', () => {
            initCharts();
        });

        document.addEventListener('DOMContentLoaded', () => {
            initCharts();
        });

        function initCharts() {
            // Data from Livewire
            const trendData = @json($shipmentTrendData);
            const productsData = @json($topProductsData);

            // 1. Shipment Trend Chart (Combo Bar + Line)
            const trendOptions = {
                series: [{
                    name: 'Số lượng xuất',
                    type: 'column',
                    data: trendData.quantities
                }, {
                    name: 'Doanh thu (VNĐ)',
                    type: 'line',
                    data: trendData.revenues
                }],
                chart: {
                    height: 400,
                    type: 'line',
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                stroke: {
                    width: [0, 4],
                    curve: 'smooth'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '50%'
                    }
                },
                colors: ['#1677FF', '#52C41A'],
                labels: trendData.labels,
                yaxis: [{
                    title: { text: 'Số lượng xuất' },
                }, {
                    opposite: true,
                    title: { text: 'Doanh thu (VNĐ)' },
                    labels: {
                        formatter: function (val) {
                            return new Intl.NumberFormat('vi-VN').format(val);
                        }
                    }
                }],
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined") {
                                return new Intl.NumberFormat('vi-VN').format(y);
                            }
                            return y;
                        }
                    }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'left'
                }
            };

            const trendChart = new ApexCharts(document.querySelector("#shipmentTrendChart"), trendOptions);
            trendChart.render();

            // 2. Top 20 Products Comparison Chart (Grouped Bar)
            const productOptions = {
                series: [{
                    name: 'Lượng Nhập (Mua)',
                    data: productsData.imports
                }, {
                    name: 'Lượng Xuất (Bán)',
                    data: productsData.exports
                }],
                chart: {
                    type: 'bar',
                    height: 500,
                    toolbar: { show: false },
                    fontFamily: 'Inter, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        dataLabels: { position: 'top' },
                        borderRadius: 4
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetX: -6,
                    style: { fontSize: '10px', colors: ['#fff'] }
                },
                stroke: { show: true, width: 1, colors: ['#fff'] },
                colors: ['#3B82F6', '#F97316'],
                xaxis: {
                    categories: productsData.labels,
                    title: { text: 'Số lượng' }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center'
                }
            };

            const productChart = new ApexCharts(document.querySelector("#topProductsChart"), productOptions);
            productChart.render();
        }
    </script>

    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .shadow-sm { box-shadow: none !important; border: 1px solid #eee !important; }
        }
    </style>
</div>
