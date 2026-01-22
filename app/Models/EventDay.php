<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDay extends Model
{
    protected $fillable = [
        'event_id',
        'date',
        'label',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
