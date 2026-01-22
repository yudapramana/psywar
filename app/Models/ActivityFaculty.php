<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityFaculty extends Pivot
{
    protected $table = 'activity_faculties';

    protected $fillable = [
        'activity_id',
        'faculty_id',
        'role',
    ];
}
