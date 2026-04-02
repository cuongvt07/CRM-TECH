<?php

namespace App\Livewire\Warehouse;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\InventoryTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class WarehouseTransactionCreate extends Component
{
    public $type; // 'import' or 'export'
    public $warehouse_code;
    public $category_id;
    public $product_id;
    public $transaction_date;
    public $quantity;
    public $unit_price;
    public $partner_name;
    public $partner_phone;
    public $invoice_number;
    public $note;
    public $productSearch = '';

    public function mount($type = 'import', $warehouse_code = 'RAW_MAT', $productId = null)
    {
        $this->type = $type;
        $this->warehouse_code = $warehouse_code;
        $this->transaction_date = now()->toDateString();

        if ($productId) {
            $this->selectProduct($productId);
        }
    }

    public function updatedCategoryId()
    {
        $this->product_id = null;
        $this->unit_price = null;
        $this->productSearch = '';
    }

    public function selectProduct($id)
    {
        $this->product_id = $id;
        $product = Product::find($id);
        if ($product) {
            $this->unit_price = $product->price;
            if ($this->warehouse_code === 'FINISHED_GOODS') {
                $this->productSearch = '[' . $product->code . '] ' . $product->name;
            } else {
                $this->productSearch = $product->name;
            }
        }
    }

    public function save()
    {
        $this->validate([
            'product_id' => 'required|exists:products,id',
            'transaction_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'nullable|numeric|min:0',
            'partner_name' => 'nullable|string|max:255',
            'partner_phone' => 'nullable|string|max:20',
            'invoice_number' => 'nullable|string|max:100',
            'note' => 'nullable|string|max:500',
        ]);

        $product = Product::with('inventory')->findOrFail($this->product_id);

        // If exporting, check stock
        if ($this->type === 'export') {
            $currentStock = $product->inventory ? $product->inventory->quantity : 0;
            if ($currentStock < $this->quantity) {
                $this->addError('quantity', "Không đủ tồn kho để xuất! Tồn hiện tại: {$currentStock}");
                return;
            }
        }

        DB::beginTransaction();
        try {
            // Create transaction record
            InventoryTransaction::create([
                'product_id' => $this->product_id,
                'type' => $this->type,
                'transaction_date' => $this->transaction_date,
                'quantity' => $this->quantity,
                'unit_price' => $this->unit_price,
                'partner_name' => $this->partner_name,
                'partner_phone' => $this->partner_phone,
                'invoice_number' => $this->invoice_number,
                'note' => $this->note,
                'created_by' => Auth::id() ?? 1,
            ]);

            // Update inventory
            $inventory = Inventory::firstOrCreate(
                ['product_id' => $this->product_id],
                ['quantity' => 0]
            );

            if ($this->type === 'import') {
                $inventory->increment('quantity', $this->quantity);
            } else {
                $inventory->decrement('quantity', $this->quantity);
            }

            DB::commit();
            return $this->redirect(route('warehouse.index'), navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->addError('quantity', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $warehouse = Warehouse::where('code', $this->warehouse_code)->first();
        
        // Chỉ lấy các danh mục có sản phẩm thuộc kho này
        $categories = Category::whereHas('products', function($q) use ($warehouse) {
            $q->where('warehouse_id', $warehouse?->id);
        })->orderBy('name')->get();

        $productQuery = Product::where('warehouse_id', $warehouse?->id)
            ->with('inventory')
            ->where('status', 'active');
        
        if ($this->category_id) {
            $productQuery->where('category_id', $this->category_id);
        }

        if ($this->productSearch && !$this->product_id) {
            $productQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->productSearch . '%')
                  ->orWhere('code', 'like', '%' . $this->productSearch . '%');
            });
        }

        return view('livewire.warehouse.warehouse-transaction-create', [
            'products' => $productQuery->get(),
            'categories' => $categories,
            'warehouse' => $warehouse,
        ]);
    }
}
