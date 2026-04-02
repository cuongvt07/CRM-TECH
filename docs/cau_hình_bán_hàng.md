# 💰 MODULE BÁN HÀNG

## 5.1 Form đặt hàng

| Trường | Kiểu | Bắt buộc | Ghi chú |
|---|---|---|---|
| `customer_name` | VARCHAR(255) | ✅ | Tên khách hàng |
| `customer_tax_code` | VARCHAR(20) | | Mã số thuế (MST) |
| `customer_phone` | VARCHAR(15) | | |
| `customer_address` | TEXT | | |
| `order_date` | DATE | ✅ | Ngày đặt hàng |
| `delivery_date` | DATE | | Ngày giao hàng dự kiến |
| `note` | TEXT | | Ghi chú đơn hàng |
| `created_by` | INT (FK → users) | ✅ | Nhân viên tạo đơn |
| `status` | ENUM | ✅ | Xem bảng trạng thái |

**Chi tiết đơn hàng (Order Items):**

| Trường | Kiểu | Bắt buộc |
|---|---|---|
| `product_id` | INT (FK) | ✅ |
| `quantity` | INT | ✅ |
| `unit_price` | DECIMAL(15,2) | ✅ |
| `subtotal` | DECIMAL(15,2) | ✅ (tính tự động) |

## 5.2 Trạng thái đơn hàng

```
[PENDING] → [CONFIRMED] → [IN_PRODUCTION] → [READY] → [DELIVERED] → [COMPLETED]
                                                                            ↑
                   [CANCELLED] ←──────────────────────────────────────────┘
```

| Trạng thái | Màu hiển thị | Mô tả |
|---|---|---|
| `PENDING` | 🟡 Vàng | Vừa tạo, chờ xác nhận |
| `CONFIRMED` | 🔵 Xanh dương | Đã xác nhận, kiểm tra kho |
| `IN_PRODUCTION` | 🟠 Cam | Đang sản xuất |
| `READY` | 🟢 Xanh lá | Hàng sẵn sàng, chờ giao |
| `DELIVERED` | 🟢 Xanh đậm | Đã giao hàng |
| `COMPLETED` | ⚫ Xám | Hoàn tất, đã thanh toán |
| `CANCELLED` | 🔴 Đỏ | Đã hủy |

## 5.3 Business Logic — Xử lý đơn hàng

```
Tạo đơn hàng
    │
    ▼
Kiểm tra tồn kho (inventory check)
    │
    ├─── Đủ hàng ──────────────────→ Reserve inventory (trừ kho)
    │                                  Status: CONFIRMED → READY
    │
    └─── Thiếu hàng ───────────────→ Tạo Production Order tự động
                                       Status: CONFIRMED → IN_PRODUCTION
                                       Notification cho bộ phận SX
                                       Khi SX xong → nhập kho → Status: READY
```

## 5.4 Doanh thu nhân viên

- Mỗi đơn hàng hoàn thành (`COMPLETED`) cộng vào doanh thu của `created_by`
- Doanh thu được tính theo: `SUM(order_items.subtotal)` của đơn hàng user tạo
- Có thể xem trên Dashboard cá nhân và báo cáo Admin