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
    }

