<?php

namespace App\Livewire\Warehouse;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WarehouseTransactionCreate extends Component
{
    public $type; // 'import' or 'export'
    public $warehouse_code;
    
    // Header Info
    public $voucher_no;
    public $transaction_date;
    public $partner_id;
    public $partner_name;
    public $partner_address;
    public $partner_phone;
    public $invoice_number;
    public $note;
    public $total_amount = 0;

    // Items Grid
    public $items = [];

    // Search properties
    public $partnerSearch = '';
    public $showPartnerResults = false;
    public $showPartnerModal = false; // Điều khiển cửa sổ list chọn lọc

    public function mount($type = 'import', $warehouse_code = 'RAW_MAT', $productId = null)
    {
        $this->type = $type;
        $this->warehouse_code = $warehouse_code;
        $this->transaction_date = now()->toDateString();
        $this->voucher_no = $this->generateVoucherNo();

        // Khởi tạo 1 dòng trống
        if ($productId) {
            $product = Product::find($productId);
            if ($product) {
                $this->items[] = [
                    'product_id' => $product->id,
                    'product_name' => ($this->warehouse_code === 'FINISHED_GOODS' ? '[' . $product->code . '] ' : '') . $product->name,
                    'unit' => $product->unit,
                    'quantity' => 1,
                    'price' => (float)$product->price,
                    'amount' => (float)$product->price,
                    'search' => ($this->warehouse_code === 'FINISHED_GOODS' ? '[' . $product->code . '] ' : '') . $product->name,
                    'checked' => true, // Mặc định được chọn để in
                ];
            }
        } else {
            $this->addRow();
        }
        
        $this->calculateTotal();
    }

    public function generateVoucherNo()
    {
        $prefix = $this->type === 'import' ? 'PNK' : 'PXK';
        $date = now()->format('ymd');
        
        $lastVoucher = InventoryTransaction::where('voucher_no', 'like', "{$prefix}{$date}%")
            ->orderBy('voucher_no', 'desc')
            ->first();

        if ($lastVoucher) {
            $sequence = (int)substr($lastVoucher->voucher_no, -3) + 1;
        } else {
            $sequence = 1;
        }

        return $prefix . $date . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
    }

    public function addRow()
    {
        $this->items[] = [
            'product_id' => '',
            'product_name' => '',
            'unit' => '',
            'quantity' => 1,
            'price' => 0,
            'amount' => 0,
            'manufacturer_name' => '',
            'batch_number' => '',
            'expiry_date' => '',
            'search' => '',
            'checked' => true,
        ];
    }

    public function removeRow($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->calculateTotal();
    }

    public function togglePartnerModal()
    {
        $this->showPartnerModal = !$this->showPartnerModal;
    }

    public function selectPartner($id)
    {
        $partner = \App\Models\Customer::find($id);
        if ($partner) {
            $this->partner_id = $partner->id;
            $this->partner_name = $partner->name;
            $this->partner_address = $partner->address;
            $this->partner_phone = $partner->phone;
            $this->partnerSearch = $partner->name;
        }
        $this->showPartnerResults = false;
        $this->showPartnerModal = false;
    }

    public function printVoucher()
    {
        $this->dispatch('print-window');
    }

    public function selectProduct($index, $id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->items[$index]['product_id'] = $product->id;
            $this->items[$index]['product_name'] = $product->name;
            $this->items[$index]['unit'] = $product->unit;
            $this->items[$index]['price'] = (float)$product->price;
            $this->items[$index]['manufacturer_name'] = $product->brand ?? '';
            $this->items[$index]['search'] = ($this->warehouse_code === 'FINISHED_GOODS' ? '[' . $product->code . '] ' : '') . $product->name;
            $this->updateAmount($index);
        }
    }

    /**
     * Livewire v3 lifecycle hook: tự động kích hoạt khi bất kỳ key nào trong $items thay đổi.
     * $key có dạng "0.quantity", "1.price", v.v.
     */
    public function updatedItems($value, $key)
    {
        // Tách lấy index từ key (ví dụ: "0.quantity" -> index = 0)
        $parts = explode('.', $key);
        if (count($parts) >= 1) {
            $index = (int)$parts[0];
            $field = $parts[1] ?? '';

            // Chỉ tính lại khi quantity hoặc price thay đổi
            if (in_array($field, ['quantity', 'price'])) {
                $this->updateAmount($index);
            }
        }
    }

    public function updateAmount($index)
    {
        // Xử lý dấu phẩy/chấm do người dùng nhập (ví dụ: "1.000" hoặc "1,500")
        $rawQty   = str_replace(['.', ','], ['', '.'], (string)($this->items[$index]['quantity'] ?? 0));
        $rawPrice = str_replace(['.', ','], ['', '.'], (string)($this->items[$index]['price'] ?? 0));

        $qty   = (float)$rawQty;
        $price = (float)$rawPrice;
        
        // Cập nhật ngược lại vào items để đồng bộ giao diện (xóa dấu phẩy thừa khi nhập xong)
        $this->items[$index]['quantity'] = $qty;
        $this->items[$index]['price'] = $price;

        $this->items[$index]['amount'] = $qty * $price;
        $this->calculateTotal();
    }

    public function calculateTotal()
    {
        $this->total_amount = array_reduce($this->items, function ($carry, $item) {
            return $carry + ($item['amount'] ?? 0);
        }, 0);
    }

    public function save()
    {
        $this->validate([
            'transaction_date' => 'required|date',
            'voucher_no' => 'required|string|unique:inventory_transactions,voucher_no',
            'partner_name' => 'nullable|string|max:255',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'nullable|numeric|min:0',
        ], [
            'items.*.product_id.required' => 'Vui lòng chọn sản phẩm.',
            'items.*.quantity.min' => 'Số lượng phải từ 1 trở lên.',
        ]);

        // Kiểm tra tồn kho theo Lô nếu là xuất kho
        if ($this->type === 'export') {
            foreach ($this->items as $index => $item) {
                $batchQuery = \App\Models\InventoryBatch::where('product_id', $item['product_id'])
                    ->where('batch_number', $item['batch_number']);
                
                $batch = $batchQuery->first();
                $currentBatchStock = $batch ? $batch->quantity : 0;

                if ($currentBatchStock < $item['quantity']) {
                    $this->addError("items.{$index}.quantity", "Không đủ tồn kho cho Lô {$item['batch_number']}! Hiện tại: " . \App\Helpers\Helper::nfmt($currentBatchStock));
                    return;
                }
            }
        }

        DB::beginTransaction();
        try {
            $warehouse = Warehouse::where('code', $this->warehouse_code)->first();
            $warehouseId = $warehouse?->id;

            foreach ($this->items as $item) {
                // 1. Tạo bản ghi transaction
                InventoryTransaction::create([
                    'voucher_no' => $this->voucher_no,
                    'product_id' => $item['product_id'],
                    'type' => $this->type,
                    'transaction_date' => $this->transaction_date,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'batch_number' => $item['batch_number'] ?: 'KHO_BANDAU',
                    'expiry_date' => $item['expiry_date'] ?: null,
                    'manufacturer_name' => $item['manufacturer_name'] ?: null,
                    'partner_name' => $this->partner_name,
                    'partner_phone' => $this->partner_phone,
                    'partner_address' => $this->partner_address,
                    'invoice_number' => $this->invoice_number,
                    'note' => $this->note,
                    'created_by' => Auth::id() ?? 1,
                ]);

                // 2. Cập nhật tồn kho tổng (Master Inventory)
                $inventory = Inventory::firstOrCreate(
                    ['product_id' => $item['product_id']],
                    ['quantity' => 0]
                );

                // 3. Cập nhật tồn kho theo Lô (Batch Inventory)
                $batch = \App\Models\InventoryBatch::firstOrCreate(
                    [
                        'product_id' => $item['product_id'],
                        'batch_number' => $item['batch_number'] ?: 'KHO_BANDAU',
                        'warehouse_id' => $warehouseId,
                    ],
                    [
                        'expiry_date' => $item['expiry_date'] ?: null,
                        'manufacturer_name' => $item['manufacturer_name'] ?: null,
                        'quantity' => 0
                    ]
                );

                if ($this->type === 'import') {
                    $inventory->increment('quantity', $item['quantity']);
                    $batch->increment('quantity', $item['quantity']);
                } else {
                    $inventory->decrement('quantity', $item['quantity']);
                    $batch->decrement('quantity', $item['quantity']);
                }
            }

            DB::commit();
            
            $this->dispatch('notify', ['message' => 'Lưu phiếu thành công!', 'type' => 'success']);
            return $this->redirect(route('warehouse.index'), navigate: true);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('note', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $warehouse = Warehouse::where('code', $this->warehouse_code)->first();
        
        $partners = [];
        if (strlen($this->partnerSearch) >= 2) {
            $partners = Customer::where('name', 'like', '%' . $this->partnerSearch . '%')
                ->orWhere('customer_code', 'like', '%' . $this->partnerSearch . '%')
                ->limit(10)
                ->get();
        }

        // Lấy danh sách sản phẩm phù hợp với loại kho
        $allProducts = Product::where('status', 'active')
            ->where(function ($q) use ($warehouse) {
                // 1. Nếu đã gán kho trực tiếp
                if ($warehouse) {
                    $q->where('warehouse_id', $warehouse->id);
                }

                // 2. Dự phòng: Lấy theo mã tiền tố (nếu warehouse_id đang trống)
                if ($this->warehouse_code === 'FINISHED_GOODS') {
                    $q->orWhere('code', 'like', 'SP%');
                } elseif ($this->warehouse_code === 'RAW_MAT') {
                    $q->orWhere('code', 'like', 'NVL%');
                } elseif ($this->warehouse_code === 'SUPPLIES') {
                    $q->orWhere('code', 'like', 'VT%')
                      ->orWhere('code', 'like', 'VatTu%');
                }
            })
            ->get();

        return view('livewire.warehouse.warehouse-transaction-create', [
            'partners' => $partners,
            'allProducts' => $allProducts,
            'warehouse' => $warehouse,
        ]);
    }
}
