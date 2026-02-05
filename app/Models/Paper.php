<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paper extends Model
{
    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
        'participant_id' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    protected static function booted()
    {
        static::creating(function ($paper) {
            if (empty($paper->uuid)) {
                $paper->uuid = (string) Str::uuid();
            }
        });
    }

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function paperType()
    {
        return $this->belongsTo(PaperType::class, 'paper_type_id');
    }

    public function authors()
    {
        return $this->hasMany(PaperAuthor::class);
    }
}
