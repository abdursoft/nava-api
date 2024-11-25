<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferHistory extends Model
{
    protected $fillable = [
        'amount',
        'turnover',
        'bonus',
        'host',
        'client',
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
