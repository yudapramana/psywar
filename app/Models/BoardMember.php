<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoardMember extends Model
{
    protected $fillable = [
        'board_group_id',
        'board_sub_section_id',
        'name',
        'position',
        'order'
    ];

    public function boardGroup(): BelongsTo
    {
        return $this->belongsTo(BoardGroup::class);
    }

    public function subSection(): BelongsTo
    {
        return $this->belongsTo(BoardSubSection::class, 'board_sub_section_id');
    }
}
