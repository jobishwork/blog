<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class SuspendController extends Controller
{
    public function store($id)
    {
        $post = Post::find($id);
        if($post->is_suspended == 0)
        {
            $post->is_suspended = 1;
        }
        else
        {
            $post->is_suspended = 0;
        }
        $post->save();
        return back();
    }
}
