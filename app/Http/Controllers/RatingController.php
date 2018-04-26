<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\User;
use Auth;
use Session;

class RatingController extends Controller
{
    public function store($id,Request $request)
    {
        $post = Post::find($id);
        $user = Auth::user();
        if($user->ratings()->where('post_id', $id)->exists())
        {
            $user->ratings()->updateExistingPivot($post, ['score' => $request->rate]);
        }
        else
        {
            $user->ratings()->save($post,['score' => $request->rate]);
        }

        // return response()->json($request->rate);
    }
}
