<?php

namespace App\Models;

use Leaf\Model;

class Meme extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image_path',
        'category'
    ];

    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }
}
