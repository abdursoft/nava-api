<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Reward extends Model
{
    protected $fillable = [
        'rewardType',
        'rewardTitle',
        'txnId',
        'playerId',
        'amount',
        'currency',
        'created',
        'user_id'
    ];
}
