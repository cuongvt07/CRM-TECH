<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Inventory;
use App\Models\InventoryBatch;
use App\Models\Product;

echo "Starting data initialization...\n";

$inventories = Inventory::all();
foreach ($inventories as $inv) {
    if ($inv->quantity > 0) {
        $product = Product::find($inv->product_id);
        InventoryBatch::updateOrCreate(
            [
                'product_id' => $inv->product_id,
                'batch_number' => 'KHO_BANDAU',
                'warehouse_id' => $product->warehouse_id,
            ],
            [
                'quantity' => $inv->quantity,
                'manufacturer_name' => $product->brand ?? null,
            ]
        );
        echo "Product ID {$inv->product_id}: Migrated " . $inv->quantity . " to KHO_BANDAU\n";
    }
}

echo "Data initialization completed!\n";
