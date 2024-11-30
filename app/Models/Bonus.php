<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $fillable = [
        "rewardType",
        "rewardTitle",
        "txnId",
        "playerId",
        "amount",
        "currency",
        "user_id"
    ];
}
