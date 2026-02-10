<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Valentine extends Model
{
    protected $fillable = [
        'token',
        'author_email',
        'author_name',
        'crush_name',
        'message',
        'force_yes',
        'pincode',
        'response',
        'responded_at',
    ];

    protected $casts = [
        'force_yes' => 'boolean',
        'responded_at' => 'datetime',
    ];
}
