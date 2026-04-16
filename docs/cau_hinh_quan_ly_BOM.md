# Cấu hình Quản lý BOM (QA/QC)

Tài liệu này quy định cấu hình hiển thị và lưu vết quá trình duyệt Định mức nguyên vật liệu (BOM) bởi bộ phận QA/QC.

## 1. Lưu vết quá trình duyệt (Audit Trail)
Mỗi sản phẩm khi được chuyển trạng thái BOM sang "Chấp nhận" (Approved) phải được hệ thống lưu vết các mốc thông tin sau:
- `bom_status`: Trạng thái duyệt (Ví dụ: `approved`, `draft`).
- `bom_approved_at`: Thời gian hệ thống ghi nhận lúc người dùng bấm nút duyệt.
- `bom_approved_by`: Định danh (ID/User) của cá nhân thực hiện thao tác duyệt.

## 2. Yêu cầu giao diện hiển thị (UI)
Trên giao diện khối "Quản lý BOM" hoặc "Kiểm soát vật tư", khi trạng thái sản phẩm là Đã duyệt, hệ thống cần hiển thị rõ:
- Nhãn: **ĐÃ CHẤP NHẬN** (hoặc tương đương)
- **Tên người duyệt**: Hiển thị tên hiển thị gốc (`name`) của tài khoản phê duyệt.
- **Ngày giờ duyệt**: Hiển thị thời gian chuẩn định dạng ngày tháng năm giờ phút.

Giao diện sẽ tự động chuyển về trạng thái trống (hoặc lưu nháp) nếu Quản trị viên (Admin) thực hiện thao tác **Hủy duyệt**.
