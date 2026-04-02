<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'description',
        'duties',
        'head_id',
        'phone',
        'status',
    ];

    /**
     * Nhân viên thuộc phòng ban
     */
    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    /**
     * Trưởng phòng (User)
     */
    public function head()
    {
        return $this->belongsTo(User::class, 'head_id');
    }

    /**
     * Số nhân viên đang active
     */
    public function activeUsersCount(): int
    {
        return $this->users()->where('status', 'active')->count();
    }

    protected static function booted()
    {
        static::creating(function ($department) {
            if (!$department->code) {
                // Tự động sinh mã từ tên (Nhân sự -> NS)
                $words = explode(' ', str_replace(['-', '_'], ' ', $department->name));
                $code = '';
                foreach ($words as $w) {
                    $code .= mb_substr($w, 0, 1);
                }
                $department->code = mb_strtoupper($code);
            }
        });
    }
}
