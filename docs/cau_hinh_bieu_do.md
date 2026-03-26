# Tài liệu Cấu hình Biểu đồ (Chart Configuration)
> **Mục tiêu**: Hướng dẫn xây dựng và nhúng Biểu đồ Doanh thu theo tháng (Line Chart) vào Dashboard dùng Livewire 3.

## 1. Công nghệ sử dụng
- **Thư viện Vẽ biểu đồ chính (Client)**: Sử dụng `Chart.js` vì nó nhẹ, dễ cấu hình và vẽ trực quan qua Canvas 2D. (Tạm thời tải thẳng qua mảng CDN).
- **Tích hợp Logic (Server)**: Laravel Livewire Component truy vấn Database để tính toán doanh thu (gộp theo 12 tháng).
- **Cầu nối (Alpine.js)**: Dùng `x-data` và `x-init` của Alpine.js làm trung gian. Alpine gọi khởi tạo `new Chart()` và Laravel Blade dùng directive `@js(...)` để Inject dữ liệu PHP xuống mảng Javascript trong x-data một cách an toàn.

## 2. Truy vấn dữ liệu Server-side (PHP/Livewire)

```php
// Ở trong Component Livewire Dashboard.php, hook 'mount'
$sales = DB::table('orders')
    ->where('status', 'COMPLETED')
    ->whereYear('order_date', date('Y'))
    ->get();

// Tính tổng doanh thu map vào 12 tháng
$monthlyData = $sales->groupBy(function($order) {
    return (int) date('m', strtotime($order->order_date));
});

$data = array_fill(1, 12, 0); // Tạo mảng [1=>0, 2=>0, ..., 12=>0]
foreach ($monthlyData as $month => $orders) {
    $data[$month] = $orders->sum('total_amount');
}

$this->revenueData = array_values($data);
```

## 3. Khởi tạo Biểu đồ (Blade View)

Cấu trúc giao diện bắt dữ liệu:

```html
<div x-data="{
        initChart() {
            new Chart(this.$refs.revenueCanvas, {
                type: 'line',
                data: {
                    labels: @js($revenueLabels),
                    datasets: [{
                        label: 'Doanh thu (VNĐ)',
                        data: @js($revenueData),
                        borderColor: '#1677FF',
                        tension: 0.3,
                        fill: true,
                    }]
                }
            });
        }
    }"
    x-init="initChart()">
    
    <canvas x-ref="revenueCanvas"></canvas>
</div>
```

Bằng cách dùng `x-ref` thì không cần phải đặt thuộc tính `id="..."` cho canvas nữa, tránh bị lỗi Conflict Element trên luồng SPA của Livewire.
