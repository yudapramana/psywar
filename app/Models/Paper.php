<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $guarded = [];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

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
