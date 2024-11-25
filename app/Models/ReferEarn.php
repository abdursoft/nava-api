<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferEarn extends Model
{
    protected $fillable = [
        'user_id',
        'host_id',
        'amount',
        'today'
    ];


    /**
     * Increment type
     */
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * Generate the uuid
     */
    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
