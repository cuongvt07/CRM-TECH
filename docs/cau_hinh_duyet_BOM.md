# Cấu hình Giao diện Duyệt BOM/NVL

Tài liệu này chuẩn hóa giao diện tương tác người dùng khi thiết lập và thêm Định mức vật tư cho một Mã hàng tại Phân hệ QA/QC.

## Thiết kế Form Nhập Liệu Nội Tiếp (Inline-Table Form)
Để tối ưu hóa trải nghiệm gõ phím theo luồng thao tác từ trái sang phải, không di chuyển khung hình:
- Form biểu mẫu "Thêm Nguyên vật liệu mới" không được bố trí thành một block chức năng riêng biệt. Thay vào đó, Form này biến thành **dòng cuối cùng (last-row)** của Bảng (Table) danh sách cấu hình định mức hiện tại.
- Các ô nhập (Input) của Dòng cuối này tương ứng với các cột của bảng:
  1. **Cột Mã/Tên NVL**: Là thẻ Input Search có dropdown datalist, nằm bên tay trái cùng.
  2. **Cột Số Lượng**: Ô nhập Number số lượng định mức.
  3. **Cột Đơn Vị**: Ô nhập text.
  4. **Cột Hãng Sản Xuất**: Tự động load tương ứng với NVL vừa được chọn ở Cột 1 (Read-only).
  5. **Cột Hành Động**: Thay vì icon Xóa (Trash), hàng này hiển thị Nút **[Lưu]** để gửi dữ liệu về Database.

## Luồng hoạt động (Workflow)
1. Thao tác chọn NVL -> Nhập số lượng -> Bấm nút **Lưu** sẽ đẩy dữ liệu lên hệ thống.
2. Dòng (Row) vừa nhập lập tức trở thành một hàng bản ghi hiển thị ngay phía trên, đôn ô nhập liệu xuống dưới cùng. Dữ liệu này đẩy "lên gần thanh công cụ" (khu vực Approve bar) để tiến hành duyệt.
3. Khi Quản lý ấn nút "Chấp nhận & Khóa định mức", dòng form thêm cuối cùng này sẽ mất đi. Bảng cấu hình trở thành Read-only.
