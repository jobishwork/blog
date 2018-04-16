@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <!-- Blog Sidebar Widgets Column -->
    <div class="col-md-3 right-sidebar">
      @include('sidebar')
    </div>
    <!-- Blog Post Content Column -->
    <div class="col-lg-9">
      <h3><a href="">Users</a></h3>
      @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table">
        <thead>
          <tr>
            <th>User</th>
            <th>Email</th>
            <th>Points</th>
          </tr>
        </thead>
        <tbody>
        @if(count($users))
          @foreach($users as $user)
            <tr >
                <td>
                    <a href="{{url("users/edit/$user->id")}}">
                        @if($user->profile_photo)
                            <img alt="User Image" class="img-circle" width="30" height="30" src="{{ url('files/user/profile_photo/resized/'.$user->profile_photo) }}">
                        @else
                            <img class="img-circle" width="30" height="30" src="{{ url('images/default-user.png') }}">
                        @endif
                        {{$user->name}}
                    </a>
                </td>
                <td>{{$user->email}}</td>
                <td>
                        @php
                            $old_transaction = $user->transactions()->orderBy('created_at','desc')->get()->first();
                            if ($old_transaction)
                            {
                                $balance = $old_transaction->balance;
                            }
                            else
                            {
                                $balance = 0;
                            }
                        @endphp
                        {{$balance}}
                </td>
            </tr>
          @endForeach
        @else
            <tr>
              <td align="center"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="3">{{$users->links()}} </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
