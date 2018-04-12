<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($id)
    {
        $post = Post::find($id);
        $user = Auth::user();
        $likes = $user->likes->pluck('id')->toArray();
        if (in_array($id, $likes))
        {
            $user->likes()->detach($post);
        }
        else
        {
           $user->likes()->attach($post);
           $user->dislikes()->detach($post);
        }
        return back();
    }
}

/*
$unlocked_ids = Auth::user()->unlockedArticles->pluck('id')->toArray();

        $user = Auth::user();
        $post = Post::find($id);
        $saved_articles = $user->savedArticles()->get()->toArray();
        $saved_ids = array_pluck( $saved_articles, 'id' );
        if(in_array($id, $saved_ids))
        {
            $user->savedArticles()->detach($post);
        }
        else
        {
            // $post->savedArticles()->attach($user);
            $user->savedArticles()->attach($post);
        }

        return back();
