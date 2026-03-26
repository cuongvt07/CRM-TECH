# Cấu hình trang Hồ sơ cá nhân (User Profile)

Hệ thống cho phép người dùng xem thông tin chi tiết về tài khoản và hồ sơ nhân sự của mình.

## 1. Truy cập
- Người dùng nhấp vào **Avatar** trên thanh Header để mở Dropdown Menu.
- Nhấp chọn **"Hồ sơ cá nhân"** để chuyển hướng tới `/profile`.

## 2. Thông tin hiển thị
Trang hồ sơ hiển thị dữ liệu trực tiếp từ bảng `users` trong cơ sở dữ liệu:
- **Thông tin cơ bản**: Ảnh đại diện (Avatar), Họ tên, Email.
- **Chi tiết nhân sự**: 
  - Mã nhân viên (`code`).
  - Phòng ban (`department`).
  - Chức vụ (`role`).
  - Ngày vào làm (`hire_date`).
- **Trạng thái hệ thống**:
  - Trạng thái tài khoản (Active/Inactive).
  - Thời gian gia nhập hệ thống.

## 3. Thành phần kỹ thuật
- **Component**: `App\Livewire\Profile` chịu trách nhiệm lấy `Auth::user()` và render giao diện.
- **Route**: Được bảo vệ bởi middleware `auth`, ngăn chặn truy cập trái phép từ khách.
- **UI/UX**: Sử dụng Tailwind CSS với các thẻ Card sang trọng, kết hợp FontAwesome 6 cho các icon định danh. Biểu tượng Avatar mặc định được tự động sinh thông qua `ui-avatars.com` nếu người dùng chưa tải ảnh lên.
