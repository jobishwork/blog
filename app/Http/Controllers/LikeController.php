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
        // return Post::withCount('likes','dislikes')->find($id);
        $post = Post::find($id);
        $user = Auth::user();
        $likes = $user->likes->pluck('id')->toArray();
        if (in_array($id, $likes))
        {
            $user->likes()->detach($post);

            $count_post = Post::withCount('likes','dislikes')->find($id);
            $likes_count = $count_post->likes_count;
            $dislikes_count = $count_post->dislikes_count;
            return response()->json(['name' => 'detach','likes_count' => $likes_count,'dislikes_count' => $dislikes_count]);
        }
        else
        {
            $user->likes()->attach($post);
            $user->dislikes()->detach($post);

            // return response()->json('attach');
            $count_post = Post::withCount('likes','dislikes')->find($id);
            $likes_count = $count_post->likes_count;
            $dislikes_count = $count_post->dislikes_count;
            return response()->json(['name' => 'attach','likes_count' => $likes_count,'dislikes_count' => $dislikes_count]);
        }
        return back();
    }
}
