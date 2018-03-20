<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Session;
use Auth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest');
        $this->middleware('guest')->except('changeForm', 'change');
        $this->middleware('auth')->only('changeForm', 'change');
    }

    public function changeForm()
    {
        return view('auth/passwords/change');
    }

    public function change(Request $request)
    {
        if (!(Hash::check($request->get('current_password'), Auth::user()->password)))
        {
            // The passwords matches
            return redirect()->back()->with("error_message","Your current password does not matches with the password you provided. Please try again.");
        }
        if(strcmp($request->get('current_password'), $request->get('new_password')) == 0)
        {
            //Current password and new password are same
            return redirect()->back()->with("error_message","New Password cannot be same as your current password. Please choose a different password.");
        }
        $validatedData = $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:6|confirmed',
        'password_confirmation' =>'required',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        return redirect()->back()->with("success","Password changed successfully !");
    }
}
