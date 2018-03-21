<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Session;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('edit','update');
        $this->middleware('auth')->only('edit', 'update');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function registered(Request $request, $user)
    {
        Session::flash('message', 'Welcome '.ucfirst($user->name).'. You have been successfully registered and logged into the site.');
        return redirect()->intended($this->redirectPath());
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('Auth\editprofile',compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:users,email,'.$id,
          ]);

        DB::transaction(function () use ($request, $id)
            {
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->save();
            });
        Session::flash('success', 'Profile has been updated successfully.');
        return back();
    }
}
