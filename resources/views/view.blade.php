@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
      <!-- Blog Post Content Column -->
      <div class="col-md-3 right-sidebar">
         @include('sidebar')
      </div>      
      <div class="col-lg-9">
         <h3><a href="">{{$blog->title}}</a></h3>
         <p><span class="glyphicon glyphicon-time"></span> <i>Created on {{date('F d, Y',strtotime($blog->created_at))}} by <a href="{{url('blog/user/'.$blog->user->id)}}">{{$blog->user->name}}</a> <a class="btn btn-default btn-xs" href="">Follow</a></i></p>
         {!! nl2br(e($blog->post)) !!}
         <br><br>

            @foreach($blog->categories as $category_array)
            <a href="{{url('blog/category/'.$category_array->id)}}" class="btn btn-default btn-xs">{{$category_array->category}}</a>
            @endforeach

            <div class="row pull-right">                
                  <div class="col-md-12"  style="margin-top:8px;">
                            <a href="" class="btn btn-primary btn-xs">Save</a>
                            <a href="" class="btn btn-danger btn-xs">Report</a>
                  </div>
            </div>
            
         <hr>


         @if(Auth::check())
         <div class="well">
            <h4>Leave a Comment:</h4>
            <form method="post" action="{{url('blog/comment/'.$blog->id)}}">
               {{ csrf_field() }}
               <div class="form-group">
                  <textarea maxlength="250" name="comment" class="form-control" rows="3"></textarea>
               </div>
               <button type="submit" class="btn btn-primary">Submit</button>
            </form>
         </div>
         @else
         <div class="well">
            <h4>Leave a Comment:</h4>
            <form role="form">
               <div class="form-group">
                  <textarea class="form-control" disabled rows="3" placeholder="Please login or register to post commenets."></textarea>
               </div>
               <a href="{{url('login')}}" class="btn btn-primary">Login</a>
               <a href="{{url('register')}}" class="btn btn-primary">Register</a>
            </form>
         </div>
         @endif

         @if(count($comments))

            @foreach($comments as $comment_array)
            <div class="media">
               <div>
                  <div class="media-body"> <strong> {{$comment_array->user->name}}</strong> on {{date('F d, Y',strtotime($comment_array->created_at))}}</div>
                  {{$comment_array->comment}}
               </div>
            </div>
            @endforeach

          @else

               <div class="media">
                  <i>No comments found.</i>
               </div>

          @endif

      </div>
      <!-- Blog Sidebar Widgets Column -->

   </div>
</div>
</div>
@endsection
