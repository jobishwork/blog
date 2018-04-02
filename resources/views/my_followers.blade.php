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
      <h3><a href="">My Followers</a></h3>
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
        @if(count($followers))
          @foreach($followers as $follower)
            <tr >
              <td>{{$follower->name}}</td>
              <td>
                @if($follower->profile_photo)
                   <img class="img-circle" width="40" height="40" src="{{ url('files/user/profile_photo/resized/'.$follower->profile_photo) }}">
               @else
                    <img class="img-circle" width="40" height="40" src="{{ url('images/default-user.png') }}">
                @endif
              </td>
              <td>
                  <a href="{{url('blog/user/'.$follower->id)}}">View Articles</a>
              </td>

            </tr>
          @endForeach
        @else
            <tr>
              <td align="center"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="3">{{$followers->links()}} </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
