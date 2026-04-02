# 👥 MODULE NHÂN SỰ & PHÂN QUYỀN

## 8.1 Thông tin nhân viên

| Trường | Kiểu | Mô tả |
|---|---|---|
| `id` | INT (PK) | |
| `code` | VARCHAR(20) | Mã nhân viên (unique) |
| `name` | VARCHAR(255) | Họ tên |
| `email` | VARCHAR(255) | Email (tùy chọn) |
| `phone` | VARCHAR(15) | Số điện thoại đăng nhập (unique, bắt buộc) |
| `avatar` | VARCHAR | URL ảnh đại diện |
| `department_id` | BIGINT (FK) | Liên kết tới phòng ban |
| `role` | ENUM | Xem bảng role |
| `status` | ENUM | `active` / `inactive` / `on_leave` |
| `hire_date` | DATE | Ngày vào làm |

---

## 8.2 Hệ thống vai trò & Phân quyền (RBAC)

Các vai trò được thiết kế để phù hợp với quy trình vận hành ERP:

- **IT** (`admin`): Toàn quyền quản trị hệ thống, cấu hình và quản lý tài khoản.
- **Giám đốc** (`director`): Xem toàn bộ dữ liệu, theo dõi báo cáo tổng hợp, duyệt các lệnh quan trọng.
- **Quản lý** (`manager`): Quản lý nhân sự và công việc trong phạm vi phòng ban/bộ phận.
- **Quản đốc** (`supervisor`): Giám sát trực tiếp tại xưởng sản xuất hoặc kho bãi.
- **Tổ trưởng** (`team_leader`): Quản lý các nhóm nhỏ/tổ sản xuất.
- **Nhân viên** (`employee`): Quyền hạn cơ bản, thực hiện các nghiệp vụ được giao.

---

## 8.3 UI/UX — Giao diện CRUD Nhân viên

### 8.3.1 Danh sách nhân viên (`/employees`)
- **Dạng:** Table list responsive.
- **Cột hiển thị:** Mã NV, Nhân viên, Vai trò, Phòng ban, Trạng thái, Thao tác.
- **Bộ lọc:** Tìm kiếm live search + Lọc theo Vai trò & Trạng thái.

### 8.3.2 Chi tiết nhân viên (`/employees/{id}`)
- Profile card đầy đủ thông tin: Avatar, Vai trò (badge màu), Phòng ban, SĐT, Email, Ngày vào làm.

### 8.3.3 Thêm / Sửa nhân viên
- Form nhập liệu 2 cột.
- Trường **Phòng ban** là dropdown lựa chọn từ danh sách phòng ban thực tế.
- Trường **Vai trò** hiển thị các nhãn tiếng Việt tương ứng: IT, Giám đốc, Quản lý, Quản đốc, Tổ trưởng, Nhân viên.

### 8.3.4 Xóa an toàn (Soft Delete)
- Sử dụng cơ chế `SoftDeletes`. Khi xóa, nhân viên sẽ bị ẩn nhưng vẫn có thể khôi phục từ DB.

---

## 8.4 Design Tokens

| Vai trò | Label | Màu sắc (Badge) |
|---|---|---|
| `admin` | **IT** | Indigo |
| `director` | **Giám đốc** | Red |
| `manager` | **Quản lý** | Blue |
| `supervisor` | **Quản đốc** | Purple |
| `team_leader` | **Tổ trưởng** | Amber |
| `employee` | **Nhân viên** | Gray |
