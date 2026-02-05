<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'registration_id',
        'payment_method',
        'amount',
        'proof_file',
        'status',
        'paid_at',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function verification()
    {
        return $this->hasOne(PaymentVerification::class)
                    ->latest('verified_at');
    }


    public function verifications()
    {
        return $this->hasMany(PaymentVerification::class, 'payment_id');
    }
}
