# Cấu trúc và triển khai chức năng Đăng nhập / Đăng xuất

## 1. Giao diện Đăng xuất (Header)
Sử dụng **Alpine.js** (`x-data="{ open: false }"`) để tạo popup dropdown menu khi click vào Avatar hoặc Tên người dùng bên góc phải của `<livewire:layout.header />`.
- Nhấp ra ngoài để đóng popup (`@click.away="open = false"`).
- Tuỳ chọn "Đăng xuất" kích hoạt hàm `logout()` trong class Livewire `App\Livewire\Layout\Header`.

## 2. Trang Đăng nhập (Login)
Tạo component Livewire độc lập **`Auth\Login`**.
- **Route**: `GET /login` -> render component `Login`.
- **Layout**: Sử dụng attribute `#[Layout('components.layouts.guest')]` để ép component dùng layout trống (không dính Sidebar/Header). Layout guest lưu tại `resources/views/components/layouts/guest.blade.php`.
- **Logic**: Liên kết (wire:model) email, password và Xử lý đăng nhập thông qua `Auth::attempt(...)`.
- **Giao diện**: File `resources/views/livewire/auth/login.blade.php`, thiết kế chuẩn chỉnh với TailwindCSS và các biểu tượng mượt mà của FontAwesome 6.5.1. Dùng `wire:submit="login"` cùng `wire:loading` để hiện loading state khi đang xử lý đăng nhập.

## 3. Hoạt động của Livewire Navigation
Sử dụng `$this->redirect('/', navigate: true)` để SPA Navigation nhảy mượt mà giữa các trang sau khi thao tác Authentication, không cần tải lại toàn bộ tài nguyên (CSS/JS) trên trình duyệt.
