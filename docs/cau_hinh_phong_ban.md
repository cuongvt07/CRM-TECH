# Cấu hình module Phòng ban (Department Management)

Module này cho phép quản lý cơ cấu tổ chức của công ty, phân chia nhân sự theo các đơn vị phòng ban chuyên môn.

## 1. Tính năng chính
- **Danh sách phòng ban**: Hiển thị tất cả phòng ban, tổng số nhân viên thực tế của từng phòng.
- **Quản lý CRUD**: Thêm mới, chỉnh sửa thông tin, xóa mềm (Soft Delete) phòng ban.
- **Chi tiết phòng ban**: 
    - Xem thông tin chung (Mã phòng, Tên phòng, Trưởng phòng, Mô tả).
    - Hiển thị danh sách nhân sự thuộc phòng ban đó.
    - Chức năng tìm kiếm nhân sự trong nội bộ phòng ban.

## 2. Thông tin cơ bản (Fields)
- **Mã phòng ban** (`code`): Duy nhất, dùng để định danh nhanh (VD: HR, SALE, PROD).
- **Tên phòng ban** (`name`): Tên đầy đủ của đơn vị.
- **Trưởng phòng** (`head_id`): Được chọn từ danh sách nhân viên của chính phòng ban đó (Liên kết `head_id` -> `users.id`).
- **Liên hệ** (`phone`): Số điện thoại nội bộ hoặc số hotline của phòng.
- **Mô tả** (`description`): Chức năng, nhiệm vụ chính của phòng ban.
- **Trạng thái** (`status`): Đang hoạt động hoặc Tạm ngưng.

## 3. Phân quyền & Quản lý nhân sự
- **Phòng Nhân sự (HR)**: Có quyền cấu hình và thay đổi cơ cấu phòng ban.
- **Liên kết nhân sự**: Mỗi nhân viên được gán vào 1 phòng ban duy nhất thông qua `department_id`.
- **Tuyển chọn Trưởng phòng**: Hệ thống tự động lọc danh sách nhân viên thuộc phòng ban đó để người dùng lựa chọn làm Trưởng phòng (trong trang Chỉnh sửa).

## 4. Thành phần kỹ thuật
- **Model**: `App\Models\Department`
- **Migration**: `create_departments_table` (được chạy trước bảng `users`).
- **Livewire Components**:
    - `App\Livewire\Department\DepartmentList`
    - `App\Livewire\Department\DepartmentCreate`
    - `App\Livewire\Department\DepartmentEdit`
    - `App\Livewire\Department\DepartmentDetail`
- **Route**:
    - `/departments`: Danh sách
    - `/departments/create`: Thêm mới
    - `/departments/{id}/edit`: Chỉnh sửa
    - `/departments/{id}`: Chi tiết & Danh sách nhân sự nội bộ

## 5. Xóa an toàn (Soft Delete)
- Tương tự module Nhân viên, phòng ban được xóa bằng cơ chế **Soft Delete**.
- Khi xóa phòng ban, các nhân viên thuộc phòng đó sẽ hiển thị trạng thái "Chưa cập nhật" phòng ban (null) thay vì bị lỗi dữ liệu.
