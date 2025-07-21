<?php

namespace App\Models;

class Caption extends Model
{
    protected $fillable = ['caption', 'meme_id'];

    public function meme()
    {
        return $this->belongsTo(\App\Models\Meme::class);
    }
}
