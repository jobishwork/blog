<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\User;
use Session;
use Auth;

class MessageController extends Controller
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
    public function create($id)
    {
        $user = User::find($id);
        return view('message_create',compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$id)
    {
        $this->validate($request, [
                'subject' => 'required|max:150',
                'message' => 'required',
           ]);
        $message = new Message;
        $message->thread_id = 1;
        $message->sender_id = Auth::user()->id;
        $message->receiver_id = $id;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->Save();

        Session::flash('message','Message sent successfully');
        return back();
    }

    public function inbox()
    {
        $inboxes = Auth::user()->receivedMessages;
        return view('inbox',compact('inboxes'));
    }

    public function inboxShow($id)
    {
        $message = Message::find($id);
        return view('inbox_show',compact('message'));
    }

    public function sentMessages()
    {
        $sent_messages = Auth::user()->sentMessages;
        return view('sent_messages',compact('sent_messages'));
    }

    public function sentMessageShow($id)
    {
        $message = Message::find($id);
        return view('sent_message_show',compact('message'));
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
