<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Follower;
use Auth;
use Session;
use DB;

class FollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        // return $follower = Follower::where('following_id',$id)->get();
        DB::transaction(function () use ($id)
        {
            $following_ids = Auth::user()->followings()->pluck('following_id')->toArray();
            if(in_array($id, $following_ids))
            {
                // DB::table('users')->where('votes', '>', 100)->delete();
                Follower::where('following_id',$id)->delete();
            }
            else
            {
                $follower = new Follower;
                $follower->user_id = Auth::User()->id;
                $follower->following_id = $id;
                $follower->save();
            }

        });
        return back();
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
