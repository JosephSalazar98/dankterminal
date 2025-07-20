<?php

namespace App\Models;

class Like extends Model
{
    protected $fillable = ['telegram_user_id', 'meme_id'];

    public function meme()
    {
        return $this->belongsTo(\App\Models\Meme::class);
    }
}
