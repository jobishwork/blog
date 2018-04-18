<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Category;
use App\Post;
use App\Comment;
use Auth;

class SidebarComposer
{
    /**
    * Bind data to the view.
    *
    * @param  View  $view
    * @return void
    */

    public function compose(View $view)
    {
        if (Auth::user())
        {
            $favorite_categories = Auth::user()->favoriteCategories;
        }
        else
        {
            $favorite_categories = [];
        }

        $categories = Category::orderBy('category')->get();
        $view->with('categories', $categories)->with('favorite_categories',$favorite_categories);


   	 //    $posts = Post::orderBy('created_at','desc')->limit(5)->get();
    	// $categories = Category::orderBy('category')->get();
     //    $comments = Comment::orderBy('created_at','desc')->limit(5)->get();
   	 //    $view->with('categories', $categories)->with('posts',$posts)->with('comments',$comments);
    }
}
