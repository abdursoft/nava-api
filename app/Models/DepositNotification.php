<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepositNotification extends Model
{
    protected $fillable = [
        'type',
        'intent',
        'status',
        'amount',
        'user_id',
        'message',
    ];
}
