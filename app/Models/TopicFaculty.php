<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TopicFaculty extends Pivot
{
    protected $table = 'topic_faculties';

    protected $fillable = [
        'activity_topic_id',
        'faculty_id',
        'role',
    ];
}
