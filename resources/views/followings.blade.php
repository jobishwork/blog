@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <!-- Blog Sidebar Widgets Column -->
    <div class="col-md-3 right-sidebar">
        <div class="well" >
                @if($user->profile_photo)
                   <img class="img-rounded img-responsive" src="{{ url('files/user/profile_photo/resized/'.$user->profile_photo) }}">
                @else
                   <img class="img-rounded img-responsive" src="{{ url('images/default-user.png') }}">
                @endif
                <br>
                <a href="{{url('blog/user/'.$user->id)}}" class="btn btn-block btn-default">{{$user->name}}</a>
                <ul>
                    <li style="margin-top:10px">
                        <a  href="{{ url('message/create/'.$user->id) }}">Send Private Message</a>
                    </li>
                </ul>
            </div>

            <div class="well">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ url('/followers/'.$user->id) }}" class="btn  btn-primary">
                            {{$user->followers->count()}}
                            <br>
                            Followers
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a href="{{ url('/followings/'.$user->id) }}" class="btn btn-primary">
                            {{$user->following->count()}}
                            <br>
                            Following
                        </a>
                    </div>
                </div>
            </div>

            <div class="well" style="padding:5px;">
                <ul class="list-group" style="margin-bottom:0px;">
                    <li class="list-group-item"> <a href="{{url('newArticles')}}"> New Articles </a> </li>
                    <li class="list-group-item"> <a href="{{url('topArticles')}}"> Most Viewed Articles </a> </li>
                    <li class="list-group-item"> <a href="{{url('saved_articles')}}"> Saved Articles </a> </li>
                    <li class="list-group-item"> <a href="{{url('blog/user/'.Auth::user()->id)}}"> My Articles </a> </li>
                </ul>
            </div>

            <div class="well">
                <div class="row">
                    <div class="col-lg-12">
                        <ul style="padding-left:20px;">
                            <li>
                                <a href="{{url('privacy-policy')}}">Privacy Policy</a>
                            </li>
                            <li>
                                <a href="{{url('terms')}}">Terms</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
    </div>
    <!-- Blog Post Content Column -->
    <div class="col-lg-9">
      <h3><a href="">Followings</a></h3>
      <hr>
      @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th>Photo</th>
            <th>Articles</th>
          </tr>
        </thead>
        <tbody>
        @if(count($followings))
          @foreach($followings as $following)
            <tr >
                <td>{{$following->name}}</td>
                <td>
                    @if($following->profile_photo)
                       <img class="img-circle" width="40" height="40" src="{{ url('files/user/profile_photo/resized/'.$following->profile_photo) }}">
                    @else
                        <img class="img-circle" width="40" height="40" src="{{ url('images/default-user.png') }}">
                    @endif
              </td>
              <td>
                  <a href="{{url('blog/user/'.$following->id)}}">View Articles</a>
              </td>

            </tr>
          @endForeach
        @else
            <tr>
              <td align="center"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="3">{{$followings->links()}} </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
