<?php

use App\Models\Product;

include 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$products = Product::where('warehouse_id', 1)->get();
foreach ($products as $p) {
    if (str_contains($p->code, 'NVL')) {
        // Extract number
        $num = (int) preg_replace('/[^0-9]/', '', $p->code);
        // New format: NVL00 + 4 digits padding
        $newCode = 'NVL00' . str_pad($num, 4, '0', STR_PAD_LEFT);
        
        $oldCode = $p->code;
        $p->update(['code' => $newCode]);
        echo "Renamed: $oldCode -> $newCode\n";
    }
}
