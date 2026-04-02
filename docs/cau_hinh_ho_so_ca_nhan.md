# Cấu hình trang Hồ sơ cá nhân (User Profile)

Hệ thống cho phép người dùng xem thông tin chi tiết về tài khoản và hồ sơ nhân sự của mình.

## 1. Truy cập
- Người dùng nhấp vào **Avatar** trên thanh Header để mở Dropdown Menu.
- Nhấp chọn **"Hồ sơ cá nhân"** để chuyển hướng tới `/profile`.

## 2. Đăng nhập
- Đăng nhập bằng **Số điện thoại + Mật khẩu** (không dùng email).
- Số điện thoại là trường bắt buộc, duy nhất trong hệ thống (unique).
- Email là trường tùy chọn, không dùng để xác thực.

## 3. Thông tin hiển thị
Trang hồ sơ hiển thị dữ liệu trực tiếp từ bảng `users` trong cơ sở dữ liệu:
- **Thông tin cơ bản**: Ảnh đại diện (Avatar), Họ tên, Số điện thoại, Email (nếu có).
- **Chi tiết nhân sự**: 
  - Mã nhân viên (`code`).
  - Phòng ban (`department`).
  - Chức vụ (`role`).
  - Ngày vào làm (`hire_date`).
- **Trạng thái hệ thống**:
  - Trạng thái tài khoản (Active/Inactive) - Hiển thị nhãn Tiếng Việt.
  - Thời gian gia nhập hệ thống.

## 4. Các Tab chức năng
Trang hồ sơ hiện tại được chia làm 2 Tab chính:
1. **Hồ sơ cá nhân**: Hiển thị các thông tin chi tiết về nhân sự và hệ thống.
2. **Nhiệm vụ & Chức năng**: Hiển thị nội quy, nhiệm vụ riêng biệt của phòng ban mà nhân viên đang thuộc về (Dữ liệu từ module Phòng ban).

## 4. Thành phần kỹ thuật
- **Component**: `App\Livewire\Profile` chịu trách nhiệm lấy `Auth::user()` và render giao diện.
- **Route**: Được bảo vệ bởi middleware `auth`, ngăn chặn truy cập trái phép từ khách.
- **Auth**: Sử dụng `Auth::attempt(['phone' => ..., 'password' => ...])` thay vì email.
- **UI/UX**: Sử dụng Tailwind CSS với các thẻ Card sang trọng, kết hợp FontAwesome 6 cho các icon định danh. Biểu tượng Avatar mặc định được tự động sinh thông qua `ui-avatars.com` nếu người dùng chưa tải ảnh lên.
