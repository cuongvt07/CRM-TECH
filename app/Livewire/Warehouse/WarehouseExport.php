<?php

namespace App\Livewire\Warehouse;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Response;

class WarehouseExport
{
    public function __invoke(\Illuminate\Http\Request $request)
    {
        $type = $request->query('type', 'inventory');
        $warehouseCode = $request->query('warehouse', 'RAW_MAT');

        $warehouse = Warehouse::where('code', $warehouseCode)->first();
        if (!$warehouse) {
            abort(404, 'Kho không tồn tại');
        }

        $fileName = 'kho_' . strtolower($warehouse->code) . '_' . $type . '_' . date('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ];

        if ($type === 'inventory') {
            return $this->exportInventory($warehouse, $headers);
        } else {
            return $this->exportTransactions($warehouse, $headers);
        }
    }

    private function exportInventory(Warehouse $warehouse, array $headers)
    {
        $products = Product::where('warehouse_id', $warehouse->id)
            ->with('inventory')
            ->get();

        $callback = function () use ($products, $warehouse) {
            $file = fopen('php://output', 'w');
            // BOM for UTF-8 in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'BÁO CÁO TỒN KHO - ' . strtoupper($warehouse->name),
                'Ngày xuất: ' . date('d/m/Y H:i'),
            ]);
            fputcsv($file, []); // empty row

            fputcsv($file, [
                'Mã SP',
                'Tên Hàng Hóa',
                'Đơn Vị',
                'Tồn Tối Thiểu',
                'Tồn Tối Đa',
                'Tồn Hiện Tại',
                'Trạng Thái',
            ]);

            foreach ($products as $prod) {
                $qty = $prod->inventory ? $prod->inventory->quantity : 0;
                $status = 'Bình Thường';
                if ($prod->min_stock > 0 && $qty < $prod->min_stock) {
                    $status = 'CẢNH BÁO CẠN KHO';
                } elseif ($prod->max_stock > 0 && $qty > $prod->max_stock) {
                    $status = 'VƯỢT ĐỊNH MỨC';
                }

                fputcsv($file, [
                    $prod->code,
                    $prod->name,
                    $prod->unit,
                    $prod->min_stock,
                    $prod->max_stock ?? 'Không giới hạn',
                    $qty,
                    $status,
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    private function exportTransactions(Warehouse $warehouse, array $headers)
    {
        $transactions = InventoryTransaction::whereHas('product', function ($q) use ($warehouse) {
                $q->where('warehouse_id', $warehouse->id);
            })
            ->with(['product', 'creator'])
            ->latest()
            ->get();

        $callback = function () use ($transactions, $warehouse) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'LỊCH SỬ NHẬP/XUẤT KHO - ' . strtoupper($warehouse->name),
                'Ngày xuất: ' . date('d/m/Y H:i'),
            ]);
            fputcsv($file, []);

            fputcsv($file, [
                'Ngày/Giờ',
                'Loại GD',
                'Mã SP',
                'Tên Hàng Hóa',
                'Số Lượng',
                'Đơn Giá Vốn',
                'Đối Tác (KH/NCC)',
                'Số Chứng Từ (HĐ)',
                'Người Lập',
                'Ghi Chú',
            ]);

            foreach ($transactions as $txn) {
                fputcsv($file, [
                    $txn->created_at ? $txn->created_at->format('d/m/Y H:i') : '',
                    $txn->type === 'import' ? 'NHẬP KHO' : 'XUẤT KHO',
                    $txn->product?->code,
                    $txn->product?->name,
                    ($txn->type === 'import' ? '+' : '-') . $txn->quantity,
                    $txn->unit_price ? \App\Helpers\Helper::nfmt($txn->unit_price) : '',
                    $txn->partner_name ?? '',
                    $txn->invoice_number ?? '',
                    $txn->creator?->name ?? '',
                    $txn->note ?? '',
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
