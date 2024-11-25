<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserTurnOver extends Model
{
    protected $fillable = [
        'amount',
        'status',
        'user_id'
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

    public function user(){
        return $this->belongsTo(User::class);
    }
}
