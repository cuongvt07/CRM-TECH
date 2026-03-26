# Thay đổi Sidebar Menu: Thu gọn và mở rộng

Yêu cầu: Thanh sidebar menu chức năng có thể thu dọn (collapse) và mở rộng (expand).

## Giải pháp triển khai
Sử dụng **Alpine.js** (được tích hợp sẵn trong Livewire 3) để quản lý trạng thái giao diện phía client (client-side state) cho tính năng mở rộng/thu gọn sidebar. Cách này giúp hiệu ứng mượt mà và không cần gọi request về server.

## Các file đã cấu hình:
1. **`resources/views/layouts/app.blade.php`**:
   - Thêm trạng thái Alpine.js vảo wrapper chính: `<div x-data="{ sidebarOpen: true }">`.
2. **`resources/views/livewire/layout/sidebar.blade.php`**:
   - Sử dụng `:class="sidebarOpen ? 'w-64' : 'w-20'"` để thay đổi độ rộng của sidebar.
   - Thêm nút thu gọn/gợi mở ở góc trên cùng của sidebar (phần Header ERP).
   - Thêm thuộc tính `x-show="sidebarOpen"` vào các text của menu để tự động ẩn khi thu gọn.
   - Các biểu tượng SVG được điều chỉnh sử dụng `shrink-0` và loại bỏ margin bên phải thay bằng margin bên trái cho thẻ `span` chữ, giúp biểu tượng luôn căn giữa hoàn hảo khi sidebar bị thu hẹp.
   - Bổ sung hiệu ứng mượt mà `transition-all duration-300` khi đóng mở.

Chức năng hoàn thành và có thể test trực tiếp trên UI.
