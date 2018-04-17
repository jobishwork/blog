<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Post;
use Auth;
class ProfileController extends Controller
{
    public function index()
    {
        // return $following_ids = Auth::user()->followers->count();//pluck('id')->toArray()->count();
        if (Auth::user())
        {
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );
            $following_ids = $user->following->pluck('id')->toArray();
            $categories = $user->favoriteCategories;
            if ($following_ids)
            {
                $followig_users = true;
            }
            else
            {
                $followig_users = false;
            }

            $posts = Post::where('is_suspended',0)
                ->orderBy('vote_counts','desc')
                ->orderBy('view_count','desc')
                ->orderBy('created_at','desc')
                ->withCount('likes','dislikes')
                ->paginate(5);
            return view('my_profile',compact('posts','saved_ids','categories'));
        }
    }
}
