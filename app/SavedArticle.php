<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SavedArticle extends Model
{
	public function user()
    {
    	return $this->BelongsTo("App\User");
    }
}
