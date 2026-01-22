<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_id',
        'title',
        'type',
        'order',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function faculties()
    {
        return $this->belongsToMany(Faculty::class, 'topic_faculties')
            ->withPivot('role')
            ->withTimestamps();
    }
}
