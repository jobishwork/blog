<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Session;
use Image;
use DB;

use App\Mail\WelcomeMail;
use Mail;

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
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        Mail::to($data['email'])->send(new WelcomeMail($user));
        return $user;
    }

    protected function registered(Request $request, $user)
    {
        Session::flash('message', 'Welcome '.ucfirst($user->name).'. You have been successfully registered and logged into the site.');
        return redirect()->intended($this->redirectPath());
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('auth.editprofile',compact('user'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
                'name' => 'required|string|max:100',
                'email' => 'required|email|max:255|unique:users,email,'.$id,
                'profile_photo' => 'image|mimes:jpeg,png,jpg',
          ]);

        DB::transaction(function () use ($request, $id)
            {
                $user = User::find($id);
                $user->name = $request->name;
                $user->email = $request->email;
                if($request->profile_photo)
                    {
                        $photo_name = rand(1,9999).time().'.'.$request->profile_photo->getClientOriginalExtension();
                        $request->profile_photo->move(public_path('files/user/profile_photo/uploads'), $photo_name);
                        Image::make(public_path('files/user/profile_photo/uploads/'.$photo_name))
                                                                    ->resize(400, null, function ($constraint) { $constraint->aspectRatio(); })
                                                                    ->save('files/user/profile_photo/resized/'.$photo_name);
                        Image::make(public_path('files/user/profile_photo/uploads/'.$photo_name))
                                                                    ->fit(82, 84)
                                                                    ->save('files/user/profile_photo/thumb/'.$photo_name);
                        unlink('files/user/profile_photo/uploads/'.$photo_name);
                        if($user->profile_photo)
                        {
                            unlink('files/user/profile_photo/resized/'.$user->profile_photo);
                            unlink('files/user/profile_photo/thumb/'.$user->profile_photo);
                        }

                        $user->profile_photo = $photo_name;
                    }
                $user->save();
            });
        Session::flash('success', 'Profile has been updated successfully.');
        return back();
    }
}


// $img->resize(300, null, function ($constraint) {
//     $constraint->aspectRatio();
// });
