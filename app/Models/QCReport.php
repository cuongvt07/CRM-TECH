<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QCReport extends Model
{
    protected $fillable = [
        'production_order_id',
        'result', // pass / fail
        'pass_quantity',
        'fail_quantity',
        'failure_reason',
        'created_by',
    ];

    public function productionOrder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
