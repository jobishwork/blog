@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(Request::input('ref') == ('add-post' || 'save'))
            <p class="alert alert-warning">Please login to continue.</p>
            @endIf
            @if(Session::has('message'))
                <br>
                <p class="alert alert-danger">{!! Session::get('message') !!}</p>
            @endif
            @if(Session::has('success'))
                <br>
                <p class="alert alert-success">{!! Session::get('success') !!}</p>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                        {{ csrf_field() }}
                        <div class="form-group" row>
                           <label for="name" class="col-md-4 control-label">Login With</label>
                           <div class="col-md-6">
                               <a href="{{ url('/auth/facebook') }}" class="btn btn-social-icon btn-facebook"><i class="fa fa-facebook">Facebook</i></a>
                               <a href="{{ url('/auth/google') }}" class="btn btn-social-icon btn-google-plus"><i class="fa fa-google-plus">Google</i></a>
                               <a href="{{ url('/auth/twitter') }}" class="btn btn-social-icon btn-twitter"><i class="fa fa-twitter"></i>Twitter</a>
                           </div>
                       </div>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Email or Username </label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}"  autofocus>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password </label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ url('/password/reset') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
