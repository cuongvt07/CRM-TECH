# Tài liệu Cấu hình Giao diện (UI/UX Specification)
> **Dự án**: Hệ thống ERP Nội bộ
> **Dựa trên**: `erp_system_spec_v2.md` (Phần 12. UI/UX Design Spec)

Tài liệu này định nghĩa các quy tắc thiết kế, cấu trúc layout, và trải nghiệm người dùng (UX) áp dụng cho toàn bộ dự án ERP nội bộ.

---

## 1. Cấu trúc Layout Tổng thể

Hệ thống sử dụng cấu trúc Standard Dashboard Layout với 3 thành phần chính:

```text
┌──────────────────────────────────────────────────────────┐
│  HEADER: Logo | Global Search | 🔔 Notifications | Avatar│
├────────────┬─────────────────────────────────────────────┤
│            │                                             │
│  SIDEBAR   │              MAIN CONTENT                   │
│            │                                             │
│ Dashboard  │  ┌──────────────────────────────────────┐   │
│ Thông báo  │  │  Breadcrumb > Current Page           │   │
│ Sản phẩm   │  ├──────────────────────────────────────┤   │
│ Bán hàng   │  │                                      │   │
│ Sản xuất   │  │  Page Content                        │   │
│ Kho        │  │                                      │   │
│ Nhân viên  │  └──────────────────────────────────────┘   │
└────────────┴─────────────────────────────────────────────┘
```

## 2. Design System (Hệ thống Thiết kế)

Dự án ưu tiên sử dụng **Tailwind CSS** kết hợp cùng giao diện **Alpine.js** và **Laravel Livewire 3** nhằm đảm bảo tính đồng nhất và xây dựng UI component nhanh chóng.

### 2.1 Bảng Màu (Color Palette)

| Token (Mục đích) | Mã màu (Hex) | Ứng dụng cụ thể |
|---|---|---|
| **Primary** | `#1677FF` | Nút bấm chính, link, trạng thái đang chọn (active) |
| **Success** | `#52C41A` | Báo cáo thành công, kho đủ hàng, đơn hoàn tất |
| **Warning** | `#FAAD14` | Cảnh báo tồn kho, đơn hàng chờ xử lý |
| **Error** | `#FF4D4F` | Thông báo lỗi, thiếu hàng, quá hạn |
| **Neutral** | `#8C8C8C` | Chữ phụ, văn bản không quan trọng, border |
| **Background**| `#F5F5F5` | Nền trang tổng thể phía sau các Card |
| **Surface** | `#FFFFFF` | Nền của Card, Modal, Drawer, Table |

### 2.2 Typography (Kiểu chữ)

| Yếu tố | Kích thước | Độ đậm (Weight) |
|---|---|---|
| **H1** (Tiêu đề trang) | `24px` | Bold (700) |
| **H2** (Tiêu đề thẻ/mục) | `20px` | SemiBold (600) |
| **H3** (Tiêu đề nhóm) | `16px` | SemiBold (600) |
| **Body** (Văn bản thường) | `14px` | Regular (400) |
| **Caption** (Ghi chú nhỏ)| `12px` | Regular (400) |

---

## 3. Thành phần Giao diện Chi tiết

### 3.1 Sidebar (Thanh điều hướng bên trái)
- Luôn hiển thị Icon đi kèm Text để dễ nhận dạng.
- **Trạng thái Active**: Cụm menu được đánh dấu nền xanh nhạt, text/icon màu xanh đậm (Primary).
- **Thu gọn (Collapse)**: Khi thu gọn chỉ hiển thị Icon, hover vào sẽ hiện Tooltip giải thích.
- **Role-based (Ẩn/hiện theo quyền)**: Menu tự động lọc dựa trên quyền (VD: Sales sẽ không nhìn thấy menu Nhân sự).

### 3.2 Header (Thanh công cụ trên cùng)
- **Global Search**: Hỗ trợ phím tắt `Ctrl+K` kích hoạt modal tìm kiếm tức thì trên toàn hệ thống (Ví dụ: tìm mã vùng, mã đơn hàng).
- **Notification Bell**: Có chấm đỏ (Badge) hiển thị số lượng chưa đọc. Click xổ ra danh sách ngắn. Nhấp vào thông báo sẽ điều hướng trực tiếp đến dữ liệu tương ứng.
- **Avatar Dropdown**: Chứa profile cá nhân, Đổi mật khẩu, và Đăng xuất.

### 3.3 Giao diện Dashboard (Trang chủ)
- **KPI Cards (4 thẻ trên cùng)**:
  - Doanh thu hôm nay (Xanh lục).
  - Đơn hàng mới (Xanh dương).
  - Đang sản xuất (Cam).
  - Tồn kho cảnh báo (Đỏ).
- **Biểu đồ (Charts)**:
  - Line Chart (Đường): Doanh thu 7~30 ngày.
  - Bar Chart (Cột): Sản lượng theo ngày.
  - Pie Chart (Tròn): Phân bổ phần trăm trạng thái đơn hàng.

### 3.4 Bán hàng (Table View)
- Bảng Grid kết hợp bộ lọc mạnh mẽ (Status, Date Range, Sales Person).
- **Tương tác**: Click vào dòng dữ liệu → Mở thanh kéo **Drawer** từ bên phải màn hình để hiển thị chi tiết mã đơn, SP, lịch sử mà KHÔNG tải lại trang.

### 3.5 Sản xuất (Kanban Board)
- Sử dụng bảng Kanban kéo thả (4 cột: *Chờ xử lý, Đang làm, QC, Hoàn thành*).
- **Thẻ Card**: Thể hiện Tên SP, SL, NV phụ trách. Hạn chót (Deadline) chuyển màu đỏ nếu bị trễ.
- Hỗ trợ báo cáo tiến độ bằng cách click mở chi tiết (Drawer hoặc Modal).

### 3.6 Kho
- Bảng quản lý tồn kho có hiển thị trực quan các mức: *Tồn hiện tại, Đã giữ chỗ, Khả dụng*.
- Có biểu tượng/Màu sắc (Badge) chỉ báo trạng thái kho (Sắp hết/Đủ/Cạn).
- Mọi thao tác Nhập/Xuất/Điều chỉnh đều được gọi qua Modal nhanh để không phải sang trang mới.
- Tab lịch sử tích hợp Timeline trực quan (Realtime transaction log).

---

## 4. Trải nghiệm UX Nâng cao (Advanced UX)

Để hệ thống hoạt động muợt mà, hiện đại và giống như ứng dụng phần mềm Desktop:

1. **SPA-like Experience**: Sử dụng thuộc tính `wire:navigate` của Livewire 3 để chuyển trang tức thì mà không cần nạp lại toàn bộ tài nguyên.
2. **Realtime Updates**: 
   - Sử dụng WebSocket để đẩy cập nhật 🔴 Tồn kho, tiến độ sản xuất, Notifications ngay lập tức mà user không cần nhấn F5.
3. **Optimistic UI**: 
   - Bấm duyệt/đổi status → Giao diện phản hồi ngay lập tức (Xanh lá). Nếu API thất bại, sẽ hiển thị lỗi và tự động Rollback trạng thái về cũ.
4. **Keyboard Shortcuts**:
   - `Ctrl+K`: Global Search
   - `Ctrl+N`: Mở form nhanh Tạo đơn mới
   - `Esc`: Đóng gọn các cửa sổ Modal/Drawer đang mở.
5. **Inline Edit (Sửa nhanh tại dòng)**:
   - Double-click (nhấp đúp) vào Field trên Table để sửa tên/giá/số lượng mà không cần vào chi tiết.
6. **State Phụ trợ**:
   - `Skeleton Loading`: Khi tải danh sách kéo dài, hiện khung xám chạy animation thay vì màn hình trắng.
   - `Empty State`: Khi dữ liệu trống, hiển thị hình vẽ đồ họa đáng yêu + Nút CTA "Tạo mới ngay".

---

## 5. UI Role-Based (Hiển thị theo Vai trò)

Giao diện sẽ thay đổi trọng tâm dừa vào vị trí của người dùng:

| Role (Quyền) | Sidebar Sẽ Có | Trọng tâm hiển thị ở Dashboard |
|---|---|---|
| **Admin** | Đầy đủ không sót gì | Toàn cảnh mọi KPI, doanh thu, năng suất toàn công ty. |
| **Sales** | Bán hàng, SP, Kho (View), TB | Biểu đồ doanh số cá nhân, số lượng chốt đơn. |
| **Production** | Sản xuất, Kho (NVL), TB | Bảng Kanban công việc đang treo, tiến độ QC. |
| **Warehouse**| Kho, Sản phẩm (View), TB | Danh sách cảnh báo hàng dưới mức tối thiểu, chờ nhập/xuất. |
| **HR** | Nhân viên, TB | KPI nghỉ phép, năng suất nhân viên, thay đổi hồ sơ. |
