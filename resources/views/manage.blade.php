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
      <h3><a href="">My Articles</a></h3>
      @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table">
        <thead>
          <tr>
            <th width="70%">Title</th>
            <th>Locked</th>
            <th>date</th>
            <th>Edit</th>
          </tr>
        </thead>
        <tbody>
        @if(count($posts))
          @foreach($posts as $post_array)
            <tr >
              <td>{{$post_array->title}}</td>
              @if($post_array->is_locked)
                <td align="center">Yes</td>
              @else
                <td align="center">No</td>
              @endif
              <td>{{date('d M Y',strtotime($post_array->created_at))}}</td>
              <td><a href="{{url("blog/$post_array->id/edit")}}">Edit</a></td>
            </tr>
          @endForeach
        @else
            <tr>
              <td align="center"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="3">{{$posts->links()}} </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
