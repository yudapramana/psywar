<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperAuthor extends Model
{
    protected $fillable = [
        'paper_id',
        'author_name',
        'affiliation',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
