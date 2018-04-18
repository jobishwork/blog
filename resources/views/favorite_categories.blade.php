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
        @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
        <h3><a href="">Please select one or more Favorite Categories</a></h3>
        <hr>
        <form name="form1" method="POST" action="{{url('favorite-category')}}">
            {{ csrf_field() }}
            <div class="form-group">
                @foreach($categories as $category)
                    <div class="form-group col-lg-3">
                        <input type="checkbox" @if(in_array($category->id, $favorites)) checked="" @endif name="category[{{$category->id}}]" value="1">
                        {{$category->category}}
                    </div>
                @endForeach
            </div>
            <br>
            <hr>
            <br>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
  </div>
</div>
@endsection
