<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DepositBonus extends Model
{
    protected $fillable = [
        'game',
        'amount',
        'message',
        'turnover',
        'minimum',
        'status',
        'limit',
        'image',
        'start_date',
        'end_date',
        'description'
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

    public function userDepositBonus(){
        return $this->hasMany(UserDepositBonus::class);
    }

}
