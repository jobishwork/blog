<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportedArticle extends Model
{
    public function post()
    {
        return $this->BelongsTo("App\Post");
    }

    public function user()
    {
        return $this->BelongsTo("App\User");
    }
}
