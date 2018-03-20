@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
      <!-- Blog Post Content Column -->
      <div class="col-lg-8">
         <h3><a href="">Add Article</a></h3>
         <hr>
         @if (count($errors) > 0)
         <div class="alert alert-danger">
            <ul>
               @foreach ($errors->all() as $error)
               <li>{{ $error }}</li>
               @endforeach
            </ul>
         </div>
         @endif
         @if(Session::has('message'))
          <p class="alert alert-success">{{ Session::get('message') }}</p>
         @endif
         <form name="form1" method="POST" action="{{url('blog')}}">
            {{ csrf_field() }}
            <div class="form-group">
               <label for="title">Title</label>
               <input type="text" name="title" class="form-control" value="{{old('title')}}" id="title">
            </div>
            <div class="form-group">
               <label for="status">Locked Article?</label>
               <select class="form-control" id="is_locked" name="is_locked" onchange="LockStatusChanged(this.value)" >
                  <option @if(old('is_locked') === '0') selected @endIF value="0">No</option>
                  <option @if(old('is_locked') === '1') selected @endIF value="1">Yes</option>
               </select>
            </div>
            <div class="form-group" id="introductionContainer" @if(!old('is_locked')) style="display: none" @endIF>
               <label for="introduction">Introduction</label>
               <textarea rows="3" name="introduction" id="introduction" class="form-control">{{old('introduction')}}</textarea>
            </div>
            <div class="form-group">
               <label for="post">Article</label>
               <textarea rows="6" name="post" class="form-control">{{old('post')}}</textarea>
            </div>
            <div class="form-group">
               <label for="categories">Categories</label>
               <select multiple class="form-control js-example-basic-multiple" name="categories[]" id="categories">
                  @foreach($categories as $category_array)
                  <option @if(is_array(old('categories')) && in_array($category_array->id, old('categories'))) selected  @endIF value="{{$category_array->id}}">{{$category_array->category}}</option>
                  @endForeach
               </select>
            </div>
            <div class="form-group">
               <label for="status">Status</label>
               <select class="form-control" id="status" name="status">
                  <option @if(old('status') === '1') selected @endIF value="1">Publish</option>
                  <option @if(old('status') === '0') selected @endIF value="0">Draft</option>
               </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
         </form>
      </div>
      <!-- Blog Sidebar Widgets Column -->
      <div class="col-md-4 right-sidebar">
         @include('sidebar')
      </div>
   </div>
</div>
</div>
      <script>
         function LockStatusChanged(value)
         {
            if(parseInt(value))
               $('#introductionContainer').show();
            else
               $('#introductionContainer').hide();
         }
      </script>
@endsection
