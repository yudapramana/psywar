<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BoardSubSection extends Model
{
    protected $fillable = ['board_group_id', 'name', 'order'];

    public function boardGroup(): BelongsTo
    {
        return $this->belongsTo(BoardGroup::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(BoardMember::class)->orderBy('order');
    }
}
