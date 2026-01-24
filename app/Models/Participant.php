<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nik',
        'full_name',
        'email',
        'mobile_phone',
        'institution',
        'participant_category_id',
        'registration_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function participantCategory()
    {
        return $this->belongsTo(ParticipantCategory::class, 'participant_category_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function papers()
    {
        return $this->hasMany(Paper::class);
    }
}
