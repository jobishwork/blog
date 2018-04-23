<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class EmailController extends Controller
{
    public function send()
    {
        Mail::send('emails.welcome', ['title' => 'Hai', 'content' => 'Hallow'], function ($message)
        {

            $message->from('me@gmail.com', 'Christian Nwamba');

            $message->to('chrisn@scotch.io');

        });
         return response()->json(['message' => 'Request completed']);
    }
}
