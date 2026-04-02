# 📦 MODULE SẢN PHẨM

## 4.1 Thông tin sản phẩm

| Trường | Kiểu | Bắt buộc | Mô tả |
|---|---|---|---|
| `id` | INT (PK) | ✅ | Auto increment |
| `code` | VARCHAR(50) | ✅ | Mã sản phẩm, unique |
| `name` | VARCHAR(255) | ✅ | Tên sản phẩm |
| `description` | TEXT | | Mô tả chi tiết |
| `unit` | VARCHAR(20) | ✅ | Đơn vị: cái, kg, m, hộp... |
| `price` | DECIMAL(15,2) | ✅ | Giá bán |
| `min_stock` | INT | ✅ | Tồn kho tối thiểu (ngưỡng cảnh báo) |
| `category_id` | INT (FK) | | Danh mục sản phẩm |
| `status` | ENUM | ✅ | `active` / `inactive` |
| `created_at` | TIMESTAMP | ✅ | |
| `updated_at` | TIMESTAMP | ✅ | |

## 4.2 BOM — Bill of Materials (Nguyên vật liệu)

Mỗi sản phẩm có thể có danh sách NVL cần thiết để sản xuất:

| Trường | Kiểu | Mô tả |
|---|---|---|
| `product_id` | INT (FK) | Sản phẩm thành phẩm |
| `material_id` | INT (FK) | Nguyên vật liệu |
| `quantity` | DECIMAL | Số lượng NVL / 1 đơn vị SP |
| `unit` | VARCHAR | Đơn vị NVL |

## 4.3 Business Rules

- Mã sản phẩm (`code`) phải duy nhất trong toàn hệ thống
- Sản phẩm `inactive` không xuất hiện trong form đặt hàng
- Khi `tồn kho < min_stock` → tự động gửi notification cảnh báo
- Không được xóa sản phẩm đã có lịch sử đơn hàng (soft delete)
