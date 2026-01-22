<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentVerification extends Model
{
    protected $fillable = [
        'payment_id',
        'verified_by',
        'verified_at',
        'notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
