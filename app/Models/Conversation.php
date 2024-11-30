<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{

    protected $fillable = [
        'file',
        'status',
        'message',
        'file_type',
        'sender_id',
        'receiver_id'
    ];
}
