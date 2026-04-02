<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bom extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'material_id', // also a Product
        'quantity',
        'unit',
    ];

    /**
     * The finished product this BOM item belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * The raw material/component.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'material_id');
    }
}
