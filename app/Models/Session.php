<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'event_day_id',
        'room_id',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function day()
    {
        return $this->belongsTo(EventDay::class, 'event_day_id');
    }

    public function eventDay()
    {
        return $this->belongsTo(EventDay::class, 'event_day_id');
    }


    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
