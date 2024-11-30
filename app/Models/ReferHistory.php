<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferHistory extends Model
{
    protected $fillable = [
        'amount',
        'turnover',
        'bonus',
        'status',
        'intent',
        'user_id'
    ];
}
