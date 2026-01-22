<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    protected $fillable = [
        'participant_id',
        'paper_type_id',
        'title',
        'file_path',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    public function type()
    {
        return $this->belongsTo(PaperType::class, 'paper_type_id');
    }

    public function authors()
    {
        return $this->hasMany(PaperAuthor::class);
    }
}
