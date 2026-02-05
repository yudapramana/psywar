<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'theme',
        'description',
        'start_date',
        'end_date',
        'location',
        'venue',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
        'submission_open_at'     => 'datetime',
        'submission_deadline_at' => 'datetime',
        'notification_date'      => 'datetime',
        'submission_close_at'    => 'datetime',
    ];

    public function days()
    {
        return $this->hasMany(EventDay::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }
}
