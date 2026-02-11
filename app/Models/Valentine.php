<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Valentine extends Model
{
    protected $fillable = [
        'slug',
        'sender_email',
        'sender_name',
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($valentine) {
            if (empty($valentine->slug)) {
                $valentine->slug = Str::random(32);
            }
        });
    }
}
