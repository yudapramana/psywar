<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrationItem extends Model
{
    protected $fillable = [
        'registration_id',
        'activity_id',
        'activity_type',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}
