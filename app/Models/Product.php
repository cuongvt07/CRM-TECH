<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'image_path',
        'unit',
        'price',
        'min_stock',
        'max_stock',
        'brand',
        'category_id',
        'warehouse_id',
        'location',
        'status',
        'bom_status',
        'bom_approved_at',
        'bom_approved_by',
    ];

    protected $casts = [
        'bom_approved_at' => 'datetime',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the inventory for the product.
     */
    public function inventory(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Inventory::class);
    }

    /**
     * Get the warehouse for the product.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the bill of materials for the product.
     */
    public function boms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bom::class, 'product_id');
    }

    /**
     * Get the user who approved the BOM.
     */
    public function bomApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'bom_approved_by');
    }
}
