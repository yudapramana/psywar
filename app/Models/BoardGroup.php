<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardGroup extends Model
{
    protected $fillable = ['name', 'order'];
    
    public function subSections(): HasMany
    {
        return $this->hasMany(BoardSubSection::class)->orderBy('order');
    }

    public function members(): HasMany
    {
        return $this->hasMany(BoardMember::class)->orderBy('order');
    }
}
