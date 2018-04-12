<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Auth;

class DislikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store($id)
    {
        $post = Post::find($id);
        $user = Auth::user();
        $likes = $user->dislikes->pluck('id')->toArray();
        if (in_array($id, $likes))
        {
            $user->dislikes()->detach($post);
        }
        else
        {
           $user->dislikes()->attach($post);
           $user->likes()->detach($post);
        }
        return back();
    }
}
