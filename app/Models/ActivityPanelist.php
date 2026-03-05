<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityPanelist extends Model
{
    protected $fillable = [
        'activity_id',
        'name'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
