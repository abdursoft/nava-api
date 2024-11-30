<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDepositBonus extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'deposit_bonus_id',
        'bonus_amount'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function depositBonus(){
        return $this->belongsTo(DepositBonus::class);
    }
}
