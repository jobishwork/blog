<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Session;
use Image;
use Auth;
use DB;
use App\Mail\WelcomeMail;
use Mail;
use Illuminate\Auth\Events\Registered;
use Carbon\Carbon;

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
        $this->middleware('guest')->except('edit','update','confirm');
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
        $confirmation_code = str_random(30);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_code' => $confirmation_code,
        ]);

        Mail::to($data['email'])->send(new WelcomeMail($user));
        return $user;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        // $this->guard()->login($user);

        return $this->registered($request, $user)?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        Session::flash('message', 'Welcome '.ucfirst($user->name).'. You have been successfully registered.Thanks for signing up! Please check your email.');
        return redirect()->intended($this->redirectPath());
    }

    public function confirm($confirmation_code)
    {
        if( ! $confirmation_code)
        {
            return redirect('/login')->with('message', 'Invalid Request');
        }
        $user = User::whereConfirmationCode($confirmation_code)->first();
        if ( ! $user)
        {
            return redirect('/login')->with('message', 'Invalid Request');
        }
        $time = Carbon::parse($user->updated_at);
        $now = Carbon::now();
        $duration = $time->diffInMinutes($now);
        if($duration>1440)
            return redirect('/login')->with('message', 'Email confirmation code has been expired. <a href="verify/resend/'.$user->id.'">Resend confirmation mail</a>' );
        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();
        Session::flash('message', 'Welcome '.ucfirst($user->name).'. You have been successfully verified your account and logged into the site.');
        Auth::login($user);
        return redirect()->intended($this->redirectPath());
    }

    public function resend($id)
    {
        $user = User::find($id);
        $confirmation_code = str_random(30);
        $user->confirmation_code = $confirmation_code;
        $user->save();
        Mail::to($user->email)->send(new WelcomeMail($user));
        return redirect('/login')->with('success', 'Confirmation email hass been sent successfully.');
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
