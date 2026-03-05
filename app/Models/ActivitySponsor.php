<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivitySponsor extends Model
{
    protected $fillable = [
        'activity_id',
        'name',
        'logo_url'
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
