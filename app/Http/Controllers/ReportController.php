<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ReportedArticle;
use App\Post;
use App\User;
use Auth;
use DB;

class ReportController extends Controller
{
    public function index()
    {
        if (!Auth::user()) return redirect('/login');
        $reported_articles = ReportedArticle::groupby('post_id')
                                                    ->selectRaw("*, count(*) as count")
                                                    ->orderBy('count','desc')
                                                    ->paginate(10);
        // return $reported_articles->post;
        $suspended_ids = Post::where('is_suspended',1)->pluck('id')->toArray();
        return view('reported_articles',compact('reported_articles','suspended_ids'));
    }

    public function store($id)
    {
        if (!Auth::user()) return redirect('/login');
        $post = Post::find($id);
        $user = Auth::user();
        $reoported_ids = $user->reportedArticles->pluck('id')->toArray();
        if (in_array($id, $reoported_ids))
        {
            $user->reportedArticles()->detach($post);
            $reported_articles = Post::withCount('reportedArticles')->find($id);
            $reported_articles_count = $reported_articles->reported_articles_count;
            return response()->json(['name' => 'detach']);
        }
        else
        {
            $user->reportedArticles()->attach($post);
            $reported_articles = Post::withCount('reportedArticles')->find($id);
            $reported_articles_count = $reported_articles->reported_articles_count;
            return response()->json(['name' => 'attach']);
        }
        return back();
    }
}
