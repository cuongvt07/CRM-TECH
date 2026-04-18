<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'voucher_no',
        'product_id',
        'batch_number',
        'location',
        'type',      // import/export/adjust/reserve/release
        'transaction_date',
        'quantity',
        'reference_type',
        'reference_id',
        'note',
        'created_by',
        'partner_name',
        'manufacturer_name',
        'partner_address',
        'partner_phone',
        'invoice_number',
        'unit_price',
        'salesperson_name',
        'qa_inspection_status',
        'qa_status',
        'qa_note',
        'qa_inspector_id',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'transaction_date' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
