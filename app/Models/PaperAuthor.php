<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperAuthor extends Model
{
    protected $guarded = [];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
