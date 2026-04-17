# Cấu Hình Module: Danh Mục Nguyên Vật Liệu (Phân hệ QA/QC)

Trang **Danh mục nguyên vật liệu** trong module QA/QC đóng vai trò hiển thị và kiểm soát chất lượng của các vật tư/nguyên liệu mới được nhập vào kho. 

Dưới đây là cấu trúc hiển thị danh sách và quy trình quản lý trạng thái:

## 1. Cấu trúc bảng hiển thị (Table Columns)

- **Mã SP**: Mã định danh duy nhất (tự động sinh hoặc nhập tay).
- **Tên SP**: Tên đầy đủ của nguyên vật liệu.
- **ĐVT**: Đơn vị tính (Kg, Chai, Mét, Cái...).
- **Số lượng tồn**: Tự động đồng bộ và lấy thông tin tồn kho thực tế từ **Kho Nguyên Vật Liệu** sang.
- **Hiện trạng** (Quá trình kiểm tra chất lượng):
  - 🔴 **`Chưa kiểm tra` (Chữ màu Đỏ)**: Trạng thái mặc định. Khi bộ phận kho vừa chốt xác nhận nhập kho xong, hệ thống tự động đưa NVL đó vào danh sách này với trạng thái "Chưa kiểm tra".
  - 🟡 **`Đang kiểm tra` (Chữ màu Vàng)**: Khi User thuộc bộ phận QA/QC (đã được cấp quyền) bấm vào xử lý lô hàng/NVL đó, trạng thái sẽ tự động cập nhật thành "Đang kiểm tra".
- **Tình trạng** (Kết quả đánh giá/phê duyệt của QA/QC):
  - **`Chưa duyệt`**: NVL mới nhập hoặc đang trong quá trình xét nghiệm, kiểm tra tiêu chuẩn.
  - **`Đã duyệt`**: NVL đạt chuẩn chất lượng, QA đã "pass" để cho phép mang ra sản xuất.
- **Ghi chú**: Không gian lưu trữ các lưu ý, kết quả kiểm tra sơ bộ, hoặc nguyên nhân lô hàng cần kiểm tra kỹ hơn.

## 2. Quy trình luân chuyển dữ liệu cơ bản

1. **Bước 1 (Kho thao tác):** Kho tiến hành Nhập kho nguyên vật liệu và xác nhận phiếu nhập.
2. **Bước 2 (Hệ thống ghi nhận):** Mã NVL vừa nhập xuất hiện ở *Danh mục NVL* của module QA/QC và tự động gán nhãn 🔴 **Chưa kiểm tra** + **Chưa duyệt**.
3. **Bước 3 (QA tiếp nhận):** Nhân viên QA vào màn hình này, thao tác click vào phiếu/NVL, trạng thái lập tức chuyển sang 🟡 **Đang kiểm tra**.
4. **Bước 4 (QA kết luận):** Sau khi có kết quả thực tế, hệ thống tiến hành cập nhật Tình trạng thành **Đã duyệt** hoặc Từ chối,... kèm theo ghi chú công việc.

## 3. Các chức năng hỗ trợ trên thanh công cụ

- **Hộp kiểm (Checkbox)**: Cho phép chọn hàng loạt các mã nguyên vật liệu trong danh sách.
- **Nút "IN PHIẾU CHỌN"**: Xuất dữ liệu in cho các mục đã được tích chọn bằng hộp kiểm.
- **Nút "THÊM MỚI NVL"**: Form thêm nhanh nguyên vật liệu mới vào danh mục hệ thống.
  - **Dữ liệu yêu cầu**: Chỉ cần nhập **Tên nguyên vật liệu** và **Đơn vị tính**.
  - **Dữ liệu tự động**: Mã SP sẽ được hệ thống tự động đánh số dạng `NVL-xxxxx`. các dữ liệu tồn kho sẽ được cập nhật sau khi có phiếu nhập thực tế.

