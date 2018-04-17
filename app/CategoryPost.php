<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    protected $table = 'category_post';

    public function posts()
    {
        return $this->BelongsTo('App\Post');
    }

    public function categories()
    {
        return $this->BelongsTo('App\Category');
    }
}
