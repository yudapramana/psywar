<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingItem extends Model
{
    protected $fillable = [
        'participant_category_id',
        'package_type',
        'workshop_quota',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function participantCategory()
    {
        return $this->belongsTo(ParticipantCategory::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
