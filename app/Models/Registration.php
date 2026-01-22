<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'event_id',
        'participant_id',
        'pricing_item_id',
        'status',
        'total_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function pricingItem()
    {
        return $this->belongsTo(PricingItem::class);
    }

    public function items()
    {
        return $this->hasMany(RegistrationItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
