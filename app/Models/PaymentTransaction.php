<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'intent',
        'status',
        'wallet',
        'payment',
        'bonus_id',
        'pay_intent',
        'payable_amount'
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

    public function user() {
        return $this->belongsTo(User::class);
    }
}
