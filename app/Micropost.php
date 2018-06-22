<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
    public function beingfavorite()
    {
        return $this->belongsToMany(User::class, 'user_fav', 'user_id', 'fav_id')->withTimestamps();
    }
    
    
}