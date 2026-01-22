<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'institution',
        'specialty',
    ];

    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_faculties')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function topics()
    {
        return $this->belongsToMany(ActivityTopic::class, 'topic_faculties')
            ->withPivot('role')
            ->withTimestamps();
    }
}
