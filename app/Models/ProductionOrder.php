<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionOrder extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'status', // pending, in_progress, qc, completed, failed
        'assigned_to',
        'start_date',
        'end_date',
        'actual_end_date',
        'note',
        'warehouse_status',
        'warehouse_note',
        'warehouse_confirmed_by',
        'warehouse_confirmed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'actual_end_date' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Check if enough materials are available in stock for this production order.
     */
    public function getMaterialStatus()
    {
        $product = $this->product()->with('boms.material.inventory')->first();
        if (!$product || $product->boms->isEmpty()) {
            return ['status' => 'no_bom', 'missing' => []];
        }

        $missing = [];
        $hasEnough = true;

        foreach ($product->boms as $bom) {
            $required = $bom->quantity * $this->quantity;
            $current = $bom->material->inventory ? $bom->material->inventory->quantity : 0;
            
            if ($current < $required) {
                $hasEnough = false;
                $missing[] = [
                    'name' => $bom->material->name,
                    'required' => $required,
                    'current' => $current,
                    'shortage' => $required - $current,
                    'unit' => $bom->unit
                ];
            }
        }

        return [
            'status' => $hasEnough ? 'sufficient' : 'insufficient',
            'missing' => $missing
        ];
    }
}
