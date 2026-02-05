<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class ServiceAccount extends Model
{
    use HasApiTokens;

    protected $fillable = [
        'name',
        'is_active',
        'encrypted_token'
    ];
}
