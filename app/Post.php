<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title','post','status'];

    public function user()
    {
    	return $this->BelongsTo("App\User");
    }

    public function comments()
    {
    	return $this->hasMany("App\Comment")->orderBy('id','desc');
    }

    public function categories()
    {
        return $this->BelongsToMany("App\Category","category_post");
    }

    public function view_count()
    {
        return $this->hasOne("App\ViewCount");
    }

    public function savedArticles()
    {
        return $this->BelongsToMany("App\User","saved_articles");
    }

    public function likes()
    {
        return $this->BelongsToMany("App\User","likes");
    }

    public function dislikes()
    {
        return $this->BelongsToMany("App\User","dislikes");
    }

    public function reportedArticles()
    {
        return $this->BelongsToMany("App\User","reported_articles");
    }

    public function ratings()
    {
        return $this->BelongsToMany("App\User","post_ratings")->withPivot('score');;
    }
}