<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Conversation extends Model
{

    protected $fillable = [
        'file',
        'status',
        'message',
        'file_type',
        'sender_id',
        'receiver_id'
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
