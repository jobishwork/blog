@extends('layouts.app')
@section('content')
<div class="container">
    @php
        $user = $blog->user
    @endphp
    <div class="row">
    <!-- Blog Post Content Column -->
    <div class="col-md-3 right-sidebar">
        <div class="well" >
         @if($blog->user->profile_photo)
                   <img class="img-rounded img-responsive" src="{{ url('files/user/profile_photo/resized/'.$blog->user->profile_photo) }}">
                @else
                   <img class="img-rounded img-responsive" src="{{ url('images/default-user.png') }}">
                @endif
                <br>
                <a href="{{url('blog/user/'.$user->id)}}" class="btn btn-block btn-default">{{$blog->user->name}}</a>
                <ul>
                    <li style="margin-top:10px">
                        <a  href="{{ url('message/create/'.$user->id) }}">Send Private Message</a>
                    </li>
                </ul>
            </div>

            <div class="well">
                <div class="row">
                    <div class="col-md-6">
                        <a href="{{ url('/followers/'.$blog->user->id) }}" class="btn  btn-primary">
                            {{$blog->user->followers->count()}}
                            <br>
                            Followers
                        </a>
                    </div>
                    <div class="col-md-6 ">
                        <a href="{{ url('/followings/'.$blog->user->id) }}" class="btn btn-primary">
                            {{$blog->user->following->count()}}
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
                    @if(Auth::user())
                        <li class="list-group-item"> <a href="{{url('blog/user/'.Auth::user()->id)}}"> My Articles </a> </li>
                    @endif
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

                <a class="btn btn-info btn-xs">Rating:{{$blog->score}}</a>
                @if(Auth::user() && ($blog->user->id != Auth::User()->id))

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
                    @if(Auth::user())
                        <a class="btn btn-warning btn-xs"  href="{{ url('message/create/'.$blog->user->id) }}">Send Private Message</a>
                        <a style="width:45px;" href="javascript:void(0)" id="save_link_{{$blog->id}}" onclick="save({{$blog->id}})" class="btn btn-primary btn-xs">
                            @if($saved_ids && (in_array($blog->id, $saved_ids)))
                                Saved
                            @else
                                Save
                            @endif
                        </a>
                        <a href="" class="btn btn-danger btn-xs">Report</a>
                    @endif
                </div>
            </div>
        <hr>

        <div class="col-md-2"  id="star-rating">
            Your Rating
            <input type="radio" @if($score == 1) checked="" @endif name="example" class="rating" value="1" />
            <input type="radio" @if($score == 2) checked="" @endif name="example" class="rating" value="2" />
            <input type="radio" @if($score == 3) checked="" @endif name="example" class="rating" value="3" />
            <input type="radio" @if($score == 4) checked="" @endif name="example" class="rating" value="4" />
            <input type="radio"  @if($score == 5) checked="" @endif name="example" class="rating" value="5" />                
        </div>
        <div class="col-md-">
            <br>
            <label id="last" ></label>
            
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
   </div>
</div>
</div>
@endsection
