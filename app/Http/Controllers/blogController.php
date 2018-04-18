<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use App\Category;
use App\CategoryPost;
use App\User;
use App\Comment;
use App\ViewCount;
use App\SavedArticle;
use Auth;
use Session;
use Log;

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
            // return $unlocked_ids = Auth::user()->unlockedArticles->pluck('id')->toArray();
            $user = Auth::user();
            $saved_articles = $user->savedArticles()->get()->toArray();
            $saved_ids = array_pluck( $saved_articles, 'id' );
            // $following_ids = $user->following->pluck('id')->toArray();
            // if ($following_ids)
            // {
            //     $followig_users = true;
            // }
            // else
            // {
            //     $followig_users = false;
            // }

            // $posts = Post::whereIn('user_id',$following_ids)->paginate(5);
            $favorite_categories = $user->favoriteCategories->pluck('id');
            $favorite_posts_ids = CategoryPost::whereIn('category_id', $favorite_categories)->get()->pluck('post_id');

            $posts = Post::whereIn('id',$favorite_posts_ids)
                ->orderBy('vote_counts','desc')
                ->orderBy('view_count','desc')
                ->orderBy('created_at','desc')
                ->withCount('likes','dislikes')
                ->paginate(5);
            $categories = $user->favoriteCategories;
            return view('my_profile',compact('posts','saved_ids','categories'));
        }
        else
        {
            $posts = Post::orderBy('vote_counts','desc')
                ->orderBy('view_count','desc')
                ->orderBy('created_at','desc')
                ->paginate(5);
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
        if (!Auth::user()) return redirect('/login');
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
        if (!Auth::user()) return redirect('/login');
        $this->validate($request, $this->validationRules(), $this->customErrorMessages());

        $post = new Post;
        $post->title = $request->title;
        $post->is_locked = $request->is_locked;
        if ($request->is_locked)
        {
            $post->credits_required = $request->points_required;
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
        if (!Auth::user()) return redirect('/login');
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
        if (!Auth::user()) return redirect('/login');
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
        if (!Auth::user()) return redirect('/login');
        $post = Post::find($id);
        $post->delete();
        return redirect('blog/manage')->with('message','Article Deleted successfully');
    }

    public function manage()
    {
        if (!Auth::user()) return redirect('/login');
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
            $posts = $user->posts()
                ->orderBy('created_at','desc')
                ->withCount('likes','dislikes')
                ->paginate(5);
            return view('user_profile',compact('posts','user'));
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
        if (!Auth::user()) return redirect('/login');
        $user = Auth::user();
        $posts = $user->savedArticles()->where('is_suspended',0)->orderBy('id','desc')->withCount('likes','dislikes')->paginate(5);
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
            // $posts = Post::orderBy('created_at','desc')->paginate(5);
            $posts = Post::where('is_suspended',0)
                ->orderBy('created_at','desc')
                ->withCount('likes','dislikes')->paginate(5);
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
            $posts = Post::where('is_suspended',0)
                ->orderBy('view_count','desc')
                ->withCount('likes','dislikes')
                ->paginate(5);
            // $posts = Post::orderBy('created_at','desc')->withCount('likes','dislikes')->paginate(5);
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
        if (!Auth::user()) return redirect('/login');
        $user = Auth::user();
        $post = Post::find($id);
        $saved_articles = $user->savedArticles()->get()->toArray();
        $saved_ids = array_pluck( $saved_articles, 'id' );
        if(in_array($id, $saved_ids))
        {
            $user->savedArticles()->detach($post);
            return response()->json('detach');
        }
        else
        {
            // $post->savedArticles()->attach($user);
            $user->savedArticles()->attach($post);
            return response()->json('attach');
        }
        return back();
    }

    public function comment(Request $request, Post $blog)
    {
        if (!Auth::user()) return redirect('/login');
        if($request->comment)
        {
            $comment = new Comment;
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $blog->comments()->save($comment);
            Session::flash('message', 'Comment has been added successfully.');
        }
        return back();
    }

    public function uploadImage(Request $request)
    {
        if (!Auth::user()) return redirect('/login');
      // get current time and append the upload file extension to it,
      // then put that name to $photoName variable.
      $photoName = time().'.'.$request->file->getClientOriginalExtension();
      Log::info($photoName);
      /*
      talk the select file and move it public directory and make avatars
      folder if doesn't exsit then give it that unique name.
      */
      $request->file->move(public_path('files/posts'), $photoName);

        $filename = $request->file;

          return json_encode(["location"=> url("/files/posts/".$photoName)]);


      // /*******************************************************
      //  * Only these origins will be allowed to upload images *
      //  ******************************************************/
      // $accepted_origins = array("http://localhost", "http://192.168.1.1", "http://example.com");

      // /*********************************************
      //  * Change this line to set the upload folder *
      //  *********************************************/
      // $imageFolder = "images/";

      // reset ($_FILES);
      // $temp = current($_FILES);
      // if (is_uploaded_file($temp['tmp_name'])){
      //   if (isset($_SERVER['HTTP_ORIGIN'])) {
      //     // same-origin requests won't set an origin. If the origin is set, it must be valid.
      //     if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
      //       header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
      //     } else {
      //       header("HTTP/1.1 403 Origin Denied");
      //       return;
      //     }
      //   }


      //     If your script needs to receive cookies, set images_upload_credentials : true in
      //     the configuration and enable the following two headers.

      //   // header('Access-Control-Allow-Credentials: true');
      //   // header('P3P: CP="There is no P3P policy."');

      //   // Sanitize input
      //   if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
      //       header("HTTP/1.1 400 Invalid file name.");
      //       return;
      //   }

      //   // Verify extension
      //   if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
      //       header("HTTP/1.1 400 Invalid extension.");
      //       return;
      //   }

      //   // Accept upload if there was no origin, or if it is an accepted origin
      //   $filetowrite = $imageFolder . $temp['name'];
      //   move_uploaded_file($temp['tmp_name'], $filetowrite);

      //   // Respond to the successful upload with JSON.
      //   // Use a location key to specify the path to the saved image resource.
      //   // { location : '/your/uploaded/image/file'}
      //   echo json_encode(array('location' => $filetowrite));
      // } else {
      //   // Notify editor that the upload failed
      //   header("HTTP/1.1 500 Server Error");
      // }

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
                'points_required' => 'required_if:is_locked,1|numeric|min:1|Max:10000',
                'post' => 'required',
                'categories' => 'required',
            ];
    }

    public function customErrorMessages()
    {
        return $messages = [
            'categories.required' => 'One or more categories required.',
            'points_required.required_if' => 'The points required field is required. '
        ];
    }
}
