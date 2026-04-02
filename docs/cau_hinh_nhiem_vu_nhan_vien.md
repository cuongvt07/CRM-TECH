# 📝 CẤU HÌNH NHIỆM VỤ & CHỨC NĂNG PHÒNG BAN

Tính năng này cho phép quản lý nội quy, chức năng và nhiệm vụ riêng biệt cho từng phòng ban. Nhân viên có thể tra cứu nhanh các quy định chung của bộ phận mình ngay trên hệ thống.

## 1. Cấu hình bởi Quản trị viên (Admin/HR)
Trong module **Phòng ban**, khi Thêm mới hoặc Chỉnh sửa, hệ thống cung cấp 2 trường thông tin:
- **Mô tả ngắn**: Hiển thị nhanh ở danh sách.
- **Nhiệm vụ, Chức năng & Nội quy chi tiết**: Vùng nhập liệu lớn (Longtext) để ghi chi tiết các quy định, vai trò và phân công công việc.

## 2. Truy cập của Nhân viên (Employee View)
Nhân viên đăng nhập có thể truy cập qua menu **"Nhiệm vụ của tôi"** trên sidebar:
- Hệ thống tự động xác định phòng ban của tài khoản đang đăng nhập.
- Hiển thị thông tin: Tên phòng, Trưởng phòng, SĐT liên hệ và Toàn bộ nội dung nội quy đã được cấu hình.
- Chức năng in (Print) để lưu trữ văn bản nếu cần.

## 3. Quy trình vận hành
1. **HR/Admin** cập nhật nội dung nhiệm vụ cho từng phòng ban theo định hướng công ty.
2. **Nhân viên mới** được gán vào phòng ban sẽ chủ động vào đọc "Nhiệm vụ của tôi" để nắm bắt công việc.
3. Khi có thay đổi về quy trình, HR cập nhật lại và toàn bộ nhân viên trong phòng ban sẽ thấy thông tin mới nhất ngay lập tức.

## 4. Lưu ý kỹ thuật
- Dữ liệu được lưu tại cột `duties` bảng `departments`.
- Hiển thị theo định dạng nguyên bản (whitespace-pre-wrap).
- Nếu nhân viên chưa được gán phòng ban, hệ thống sẽ hiển thị cảnh báo hướng dẫn liên hệ IT/NS.
