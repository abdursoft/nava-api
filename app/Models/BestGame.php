<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BestGame extends Model
{
    protected $fillable = [
        'name',
        'image',
        'game_id',
        'category_id'
    ];

    /**
     * Increment type
     */
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Generate the uuid
     */
    public static function booted() {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
