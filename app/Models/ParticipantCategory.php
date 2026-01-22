<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParticipantCategory extends Model
{
    protected $fillable = ['name'];

    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    public function pricingItems()
    {
        return $this->hasMany(PricingItem::class);
    }
}
