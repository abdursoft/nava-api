<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'txnType',
        'roundId',
        'amount',
        'currency',
        'gameId',
        'betId',
        'options',
        'jpContributions',
        'device',
        'clientType',
        'clientRoundId',
        'category',
        'created',
        'completed',
        'status',
        'playerId',
        'user_id'
    ];

    protected function jpContributions(): Attribute{
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            set: fn($value) => json_encode($value)
        );
    }
}
