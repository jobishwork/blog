@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
      <!-- Blog Post Content Column -->
      <div class="col-md-3 right-sidebar">
         @include('sidebar')
      </div>      
      <div class="col-lg-9">
         <h3><a href="">Edit Article</a></h3>
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
         <form name="form1" method="POST" action="{{url('blog/'.$post->id)}}">
          <input name="_method" type="hidden" value="PATCH">
           {{ csrf_field() }}
           <div class="form-group">
             <label for="title">Title</label>
             <input type="text" name="title" value="{{$post->title}}" class="form-control" id="title">
           </div>
           <div class="form-group">
               <label for="status">Locked Article?</label>
               <select class="form-control" id="is_locked" name="is_locked" onchange="LockStatusChanged(this.value)">
                  <option value="1" @if((int)$post->is_locked) selected @endIF>Yes</option>
                  <option value="0" @if(!(int)$post->is_locked) selected @endIF>No</option>
               </select>
            </div>

           <div class="form-group" id="introductionContainer" @if(!(int)$post->is_locked) style="display: none" @endif>
               <label for="introduction">Introduction</label>
               <textarea rows="3" name="introduction" class="form-control">{{$post->introduction}}</textarea>
            </div>

           <div class="form-group">
             <label for="post">Article</label>
             <textarea rows="6" name="post" class="form-control">{{$post->post}}</textarea>
           </div>
           <div class="form-group">
             <label for="categories">Categories</label>
             <select multiple class="form-control js-example-basic-multiple" name="categories[]" id="categories">
               @foreach($categories as $category_array)
               <option @if(in_array($category_array->id,$post_categories)) selected @endif value="{{$category_array->id}}">{{$category_array->category}}</option>
               @endForeach
             </select>
           </div>
           <div class="form-group">
             <label for="status">Status</label>
             <select class="form-control" id="status" name="status">
               <option value="1" @if((int)$post->status) selected @endIF>Publish</option>
               <option value="0" @if(!(int)$post->status) selected @endIF>Draft</option>
             </select>
           </div>
           <a class="btn btn-danger" href="{{ url('post/'.$post->id.'/destroy') }}" onclick="return confirm('Do you want to Delete the Product?')" >Delete</a>
           <button type="submit" class="btn btn-primary">Update</button>
         </form>

      </div>
      <!-- Blog Sidebar Widgets Column -->
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
