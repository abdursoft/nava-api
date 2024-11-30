<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferEarn extends Model
{
    protected $fillable = [
        'user_id',
        'host_id',
        'amount',
        'today'
    ];
}
