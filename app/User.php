<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

     /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    
    /*belongsToMany()は
    
    */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }

    
    
    //followするときの動作関数
    public function follow($userId)
    {
        $exist = $this->is_following($userId);  //既にfollowしてないか確認
        $its_me = $this->id == $userId;  //自分じゃないか確認
        
        if ($exist || $its_me) {
            return false;  //followを既にしていたらfalseを返す
            
        }else {
            $this->followings()->attach($userId);
            return true;  //followしていなかったらfollowする
        }
    }
    
    public function unfollow($userId)
    {
    // confirming if already following
         $exist = $this->is_following($userId);
    // confirming that it is not you
         $its_me = $this->id == $userId;


    if ($exist && !$its_me) {
        // stop following if following
        $this->followings()->detach($userId);
        return true;
    } else {
        // do nothing if not following
        return false;
    }
    }


    public function is_following($userId) {
        return $this->followings()->where('follow_id', $userId)->exists();
        }
    
    public function feed_microposts()
        {
            $follow_user_ids = $this->followings()-> pluck('users.id')->toArray();
            $follow_user_ids[] = $this->id;
            return Micropost::whereIn('user_id', $follow_user_ids);
        }
    
    
    //favorite function
    public function favorite()
    {   return $this->belongsToMany(Micropost::class, 'user_fav', 'user_id', 'fav_id')->withTimestamps();
    
    }


    public function fav($micropostId)
    {
        $exist = $this->is_favoriting($micropostId);
        
        
        if ($exist) {
            return false;
        }else {
            $this->favorite()->attach($micropostId);
            return true;
        }
    }

    public function unfav($micropostId)
    {   $exist = $this->is_favoriting($micropostId);
    
        
        if ($exist) {
            $this->favorite()->detach($micropostId);
            return true;
        }else {
            return false;
        }
    }

    
    public function is_favoriting($micropostId) {
        return $this->favorite()->where('fav_id', $micropostId)->exists();
    }
    
}