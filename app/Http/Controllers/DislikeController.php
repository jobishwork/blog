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
        $dislikes = $user->dislikes->pluck('id')->toArray();
        if (in_array($id, $dislikes))
        {
            $user->dislikes()->detach($post);
            // return response()->json('detach');

            $count_post = Post::withCount('likes','dislikes')->find($id);
            $likes_count = $count_post->likes_count;
            $dislikes_count = $count_post->dislikes_count;
            $vote_counts = $likes_count - $dislikes_count;
            $count_post->vote_counts = $vote_counts;
            $count_post->save();
            return response()->json(['name' => 'detach','likes_count' => $likes_count,'dislikes_count' => $dislikes_count]);
        }
        else
        {
            $user->dislikes()->attach($post);
            $user->likes()->detach($post);
            // return response()->json('attach');

            $count_post = Post::withCount('likes','dislikes')->find($id);
            $likes_count = $count_post->likes_count;
            $dislikes_count = $count_post->dislikes_count;
            $vote_counts = $likes_count - $dislikes_count;
            $count_post->vote_counts = $vote_counts;
            $count_post->save();
            return response()->json(['name' => 'attach','likes_count' => $likes_count,'dislikes_count' => $dislikes_count]);
        }
        return back();
    }
}
