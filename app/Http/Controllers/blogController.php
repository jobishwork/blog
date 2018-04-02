<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\User;
use App\Comment;
use App\ViewCount;
use App\SavedArticle;
use Auth;
use Session;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user())
        {
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );
            $following_ids = $user->following->pluck('id')->toArray();
            $posts = Post::whereIn('user_id',$following_ids)->paginate(5);
            return view('list',compact('posts','saved_ids'));
        }
        else
        {
            $posts = Post::orderBy('created_at','desc')->paginate(5);
            return view('list',compact('posts'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::orderBy('category')->get();
        return view('add_form',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->validationRules(), $this->customErrorMessages());

        $post = new Post;
        $post->title = $request->title;
        $post->is_locked = $request->is_locked;
        if ($request->is_locked)
        {
            $post->credits_required = $request->credits_required;
        }
        else
        {
            $post->credits_required = 0;
        }
        $post->post = $request->post;
        $post->status = $request->status;
        $post->user_id = Auth::user()->id;
        $post->save();
        $post->categories()->attach($request->categories);

        Session::flash('message', 'New Article has been added successfully.');

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Post $blog)
    {
        // if($blog->view_count)
        // {
        //     $count = $blog->view_count->count;
        //     $blog->view_count->count = $count + 1;
        //     $blog->view_count->save();
        // }
        // else
        // {
        //     $view_count = new ViewCount;
        //     $view_count->post_id = $blog->id;
        //     $view_count->count = 1;
        //     $view_count->save();
        // }

        $count = $blog->view_count;
        $blog->view_count = $count + 1;
        $blog->save();
        if (Auth::user())
        {
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );

            $comments = $blog->comments;
            return view('view',compact('blog','comments','saved_ids'));
        }
        else
        {
            $comments = $blog->comments;
            return view('view',compact('blog','comments'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $blog)
    {
        $post = $blog;
        if($post->user_id != Auth::user()->id)
        {
            return redirect('/');
        }
        $categories = Category::orderBy('category')->get();
        $post_categories = $post->categories->pluck('id')->all();
        return view('edit_form',compact('categories','post','post_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $blog)
    {
        $this->validate($request, $this->validationRules(), $this->customErrorMessages());
        $blog->title = $request->title;
        $blog->is_locked = $request->is_locked;
        if ($request->is_locked)
        {
            $blog->credits_required = $request->credits_required;
        }
        else
        {
            $blog->credits_required = 0;
        }
        $blog->post = $request->post;
        $blog->status = $request->status;
        $blog->save();
        $blog->categories()->sync($request->categories);
        Session::flash('message', 'Article has been updated successfully.');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect('blog/manage')->with('message','Article Deleted successfully');
    }

    public function manage()
    {
        $posts = Post::orderBy('created_at','desc')->where('user_id', Auth::user()->id)->paginate(10);
        return view('manage',compact('posts'));
    }

    public function category($id)
    {
        if (Auth::user())
        {
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );

            $category = Category::find($id);
            $page_title = "Articles of category '$category->category'";
            $posts = $category->posts()->orderBy('created_at','desc')->paginate(5);
            return view('list',compact('posts','page_title','saved_ids'));
        }
        else
        {
            $category = Category::find($id);
            $page_title = "Articles of category '$category->category'";
            $posts = $category->posts()->orderBy('created_at','desc')->paginate(5);
            return view('list',compact('posts','page_title'));
        }
    }

    public function user($id)
    {
        if (Auth::user())
        {
            $user = user::find($id);
            $page_title = "Created by '$user->name'";
            $posts = $user->posts()->orderBy('created_at','desc')->paginate(5);
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );
            return view('list',compact('posts','page_title','saved_ids'));
        }
        else
        {
            $user = user::find($id);
            $page_title = "Created by '$user->name'";
            $posts = $user->posts()->orderBy('created_at','desc')->paginate(5);
            return view('list',compact('posts','page_title'));
        }
    }

    public function savedArticles()
    {
        $user = Auth::user();
        $posts = $user->savedArticles()->orderBy('id','desc')->paginate(5);
        $saved_ids = array_pluck( $posts, 'id' );
        return view('list',compact('posts','saved_ids'));
    }

    public function newArticles()
    {
        if (Auth::user())
        {
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );
            $posts = Post::orderBy('created_at','desc')->paginate(5);
            return view('list',compact('posts','saved_ids'));
        }
        else
        {
            $posts = Post::orderBy('created_at','desc')->paginate(5);
            return view('list',compact('posts'));
        }
    }

    public function topArticles()
    {
        if (Auth::user())
            {
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );
            $posts = Post::orderBy('view_count','desc')->paginate(5);
            return view('list',compact('posts','saved_ids'));
            }
        else
        {
            $posts = Post::orderBy('view_count','desc')->paginate(5);
            return view('list',compact('posts'));
        }

    }

    public function saveArticle($id)
    {
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
    }

    public function comment(Request $request, Post $blog)
    {
        if($request->comment)
        {
            $comment = new Comment;
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $blog->comments()->save($comment);
            Session::flash('message', 'Member has been updated successfully.');
        }
        return back();
    }

    public function search(Request $request)
    {
        $sword = $request->q;
        $page_title = "Searching '$request->q'";
        $posts = Post::where('title', 'like', '%' . $sword . '%')
                        ->orWhere('post', 'like', '%' . $sword . '%')
                        ->orderBy('created_at','desc')->paginate(5);
        return view('list',compact('posts','page_title','sword'));
    }


    public function validationRules()
    {
        return $rules = [
                'title' => 'required',
                'credits_required' => 'required_if:is_locked,1',
                'post' => 'required',
                'categories' => 'required',
            ];
    }

    public function customErrorMessages()
    {
        return $messages = [
            'categories.required' => 'One or more categories required.',
            'credits_required.required_if' => 'The Credits field is required. '
        ];
    }
}
