<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
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
        // $user = Auth::User();
        // return $user->following->pluck('id');
        // return Auth::User()->followers;
        // $user = User::find(3);
        // $user->followers()->attach(1);

        $user = Auth::User();
        $following_ids = $user->following->pluck('id')->toArray();
        if(in_array($id, $following_ids))
        {
            $user->following()->detach($id);
            return response()->json('detach');
        }
        else
        {
            $user->following()->attach($id);
            return response()->json('attach');
        }
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

    public function followers($id)
    {
        $user = User::find($id);
        $followers = $user->followers()->paginate(10);
        return view('followers',compact('followers','user'));
    }

    public function followings($id)
    {
        $user = User::find($id);
        $followings = $user->following()->paginate(10);
        return view('followings',compact('followings','user'));
    }

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
