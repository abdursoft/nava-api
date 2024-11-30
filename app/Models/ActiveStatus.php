<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActiveStatus extends Model
{
    protected $fillable = [
        'status',
        'user_id',
        'last_active',
        'active_note'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
