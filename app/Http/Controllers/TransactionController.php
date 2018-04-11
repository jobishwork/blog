<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Transaction;
use App\Post;
use App\User;
use Session;
use Auth;
use DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->transactions())
        {
            $transactions = $user->transactions()->orderBy('created_at','desc')->paginate(10);
        }
        return view('transaction_list',compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('buy_points',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'buy_points' => 'required|numeric',
          ]);
        $user = Auth::user();
        $old_transaction = $user->transactions()->orderBy('created_at','desc')->get()->first();
        if ($old_transaction)
        {
            $balance = $old_transaction->balance;
        }
        else
        {
            $balance = 0;
        }
        $transaction = new Transaction;
        $transaction->user_id = $user->id;
        $transaction->credits = $request->buy_points;
        $transaction->balance = $request->buy_points + $balance;
        $transaction->save();

        Session::flash('message', 'Points have been bought successfully.');
        return back();
    }

    public function unlockArticle($id)
    {
        $post = Post::find($id);
        return view('unlock_article',compact('post'));
    }

    public function unlockArticleAction($id)
    {
        $post = Post::find($id);
        $user = Auth::User();
        $old_transaction = $user->transactions()->orderBy('created_at','desc')->get()->first();
        if ($old_transaction)
        {
            $balance = $old_transaction->balance;
        }
        else
        {
            $balance = 0;
        }

        if ($balance < $post->credits_required)
        {

            return back()->with('message', 'You have insufficient balance of points. Please buy more points ');
        }
        else
        {
            $transaction = new Transaction;
            $transaction->user_id = $user->id;
            $transaction->debits = $post->credits_required;
            $transaction->balance = $balance - $post->credits_required;
            $transaction->save();

            $user->unlockedArticles()->attach($post);
            return redirect('blog/'.$id)->with('message', 'Unlocked this article successfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
