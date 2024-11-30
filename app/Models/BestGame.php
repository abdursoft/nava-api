<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestGame extends Model
{
    protected $fillable = [
        'name',
        'image',
        'game_id',
        'category_id'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
