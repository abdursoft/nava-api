<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        "name",
        "code",
        "image",
    ];

    public function bestGame(){
        return $this->hasMany(BestGame::class);
    }
}
