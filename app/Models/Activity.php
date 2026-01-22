<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'event_id',
        'category',
        'code',
        'title',
        'description',
        'is_paid',
        'quota',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function topics()
    {
        return $this->hasMany(ActivityTopic::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function registrationItems()
    {
        return $this->hasMany(RegistrationItem::class);
    }

    public function faculties()
    {
        return $this->belongsToMany(Faculty::class, 'activity_faculties')
            ->withPivot('role')
            ->withTimestamps();
    }
}
