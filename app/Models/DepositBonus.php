<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function userDepositBonus(){
        return $this->hasMany(UserDepositBonus::class);
    }

}
