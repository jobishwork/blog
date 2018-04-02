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
      <h3><a href="">My Followings</a></h3>
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
