<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserDepositBonus extends Model
{
    protected $fillable = [
        'user_id',
        'bonus_id',
        'amount',
        'bonus_amount'
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

    public function depositBonus(){
        return $this->belongsTo(DepositBonus::class);
    }
}
