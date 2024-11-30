<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'payment_id',
        'payable_amount'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
