<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /**
     * Table name (optional karena Laravel sudah otomatis)
     */
    protected $table = 'banks';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'code',
        'name',
        'account_number',
        'account_name',
        'admin_fee',
        'is_active',
        'order',
    ];

    /**
     * Cast attributes
     */
    protected $casts = [
        'is_active' => 'boolean',
        'admin_fee' => 'decimal:2',
    ];

    /**
     * Scope: hanya bank aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: urutkan sesuai kolom order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
