<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'tax_code',
        'phone',
        'email',
        'address',
        'contact_person',
        'image',
        'note',
        'type',
    ];

    protected static function booted()
    {
        static::created(function ($customer) {
            if (!$customer->customer_code) {
                $customer->customer_code = 'CUS' . str_pad($customer->id, 4, '0', STR_PAD_LEFT);
                $customer->saveQuietly();
            }
        });
    }
}
