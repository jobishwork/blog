@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
      <!-- Blog Post Content Column -->
      <div class="col-md-3 right-sidebar">
         @include('sidebar')
      </div>
        @php
            $unlocked_ids = [];
            $following_ids = [];
        if(Auth::user())
        {
            $following_ids = Auth::user()->following->pluck('id')->toArray();
            $unlocked_ids = Auth::user()->unlockedArticles->pluck('id')->toArray();
        }
        @endphp
      <div class="col-lg-9">
        @if(Session::has('message'))
            <p class="alert alert-success">{{ Session::get('message') }}</p>
        @endif
        <h3><a href="">{{$blog->title}}</a></h3>
        <p><span class="glyphicon glyphicon-time"></span>
            <i>Created on {{date('F d, Y',strtotime($blog->created_at))}} by
                <a href="{{url('blog/user/'.$blog->user->id)}}">{{$blog->user->name}}</a>
                @if (Auth::guest())
                    <a class="btn btn-default btn-xs" href="{{ url('/login?ref=follow') }}">Follow</a>
                @elseif($blog->user->id != Auth::User()->id)

                    <a style="width:60px;" id="" onclick="follow({{$blog->user->id}})" href="javascript:void(0)" class="btn btn-default btn-xs follow_link_{{$blog->user->id}}">
                        @if($following_ids && in_array($blog->user->id, $following_ids))
                            Following
                        @else
                            Follow
                        @endif
                    </a>
                @endif
            </i>
        </p>
        @if($blog->is_locked && !($unlocked_ids && in_array($blog->id,$unlocked_ids)) )
            {!! str_limit($blog->post, $limit = 500, $end = '...') !!}
            <div align="center"><a href="{{ url('unlock/article/'.$blog->id) }}" class="btn btn-primary">Unlock ({{$blog->credits_required}} Points needed)</a></div>
        @else
            {!! $blog->post !!}
        @endif
         <br><br>

            @foreach($blog->categories as $category_array)
            <a href="{{url('blog/category/'.$category_array->id)}}" class="btn btn-default btn-xs">{{$category_array->category}}</a>
            @endforeach

            <div class="row pull-right">
                <div class="col-md-12"  style="margin-top:8px;">
                    <a class="btn btn-default btn-xs">{{$blog->view_count}} views</a>
                    @if (Auth::guest())
                        <a class="btn btn-warning btn-xs"  href="{{ url('/login?ref=send') }}">Send Private Message</a>
                    @else
                        <a class="btn btn-warning btn-xs"  href="{{ url('message/create/'.$blog->user->id) }}">Send Private Message</a>
                    @endif
                    @if (Auth::guest())
                        <a class="btn btn-primary btn-xs"  href="{{ url('/login?ref=save') }}">Save</a>
                    @else
                        <a style="width:45px;" href="javascript:void(0)" id="save_link_{{$blog->id}}" onclick="save({{$blog->id}})" class="btn btn-primary btn-xs">
                            @if($saved_ids && (in_array($blog->id, $saved_ids)))
                                Saved
                            @else
                                Save
                            @endif
                        </a>
                    @endif
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
        <h4>
            Comments(
            <small>
            @if(count($comments) == 1)
                {{count($comments)}} Comment
            @else
                {{count($comments)}} Comments
            @endif
            </small>
            )
        </h4>

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
