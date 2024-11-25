<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActiveStatus extends Model
{
    protected $fillable = [
        'status',
        'user_id',
        'last_active',
        'active_note'
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
