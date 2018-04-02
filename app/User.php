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

    public function Posts()
    {
        return $this->hasMany("App\Post");
    }

    public function Comments()
    {
        return $this->hasMany("App\Comment");
    }

    public function savedArticles()
    {
        return $this->BelongsToMany("App\Post","saved_articles");
    }

    //The users those are followers of User1
    public function followers()
    {
        return $this->belongsToMany('App\User','followers','user_id','follower_id');
    }

    //User1 following these users
    public function following()
    {
        return $this->belongsToMany('App\User','followers','follower_id','user_id');
    }
}
