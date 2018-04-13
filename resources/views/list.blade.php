@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
      <div class="col-md-3 right-sidebar">
      @include('sidebar')
      </div>
      <div class="col-lg-9">
        @if(Session::has('message'))
            <p class="alert alert-success">{{ Session::get('message') }}</p>
        @endif

        @if(isset($page_title))
            <p class="info" style="font-weight: bold;">{{$page_title}}</p>
            <hr class="info">
        @endIf

        @php
            $unlocked_ids = [];
            $following_ids = [];
            $likes  = [];
            $dilikes = [];
        if(Auth::user())
        {
            $following_ids = Auth::user()->following->pluck('id')->toArray();
            $unlocked_ids = Auth::user()->unlockedArticles->pluck('id')->toArray();
            $likes = Auth::user()->likes->pluck('id')->toArray();
            $dislikes = Auth::user()->dislikes->pluck('id')->toArray();
        }
        @endphp

         @if(count($posts))
        <div class="infinite-scroll">
            @foreach($posts as $post_array)
            <h3><a href="{{url('blog/'.$post_array->id)}}">{{$post_array->title}}</a></h3>
            <p>
                @if($post_array->user->profile_photo)
                   <img class="img-circle" width="30" height="30" src="{{ url('files/user/profile_photo/resized/'.$post_array->user->profile_photo) }}">
                @endif
                <span class="glyphicon glyphicon-time"></span>
                <i>Created on {{date('F d, Y',strtotime($post_array->created_at))}} by
                    <a href="{{url('blog/user/'.$post_array->user->id)}}">{{$post_array->user->name}}</a>
                    @if (Auth::guest())
                        <a class="btn btn-default btn-xs" href="{{ url('/login?ref=follow') }}">Follow</a>
                    @elseif($post_array->user->id != Auth::User()->id)
                        <a style="width:60px;" id="" onclick="follow({{$post_array->user->id}})" href="javascript:void(0)" class="btn btn-default btn-xs follow_link_{{$post_array->user->id}}">
                            @if($following_ids && in_array($post_array->user->id, $following_ids))
                                Following
                            @else
                                Follow
                            @endif
                        </a>
                    @endif
                </i>
            </p>
            <p>
               @if($post_array->is_locked && !($unlocked_ids && in_array($post_array->id,$unlocked_ids)) )
                  {!! str_limit($post_array->post, $limit = 500, $end = '...') !!}
                  <div align="center"><a href="{{ url('unlock/article/'.$post_array->id) }}" class="btn btn-primary">Unlock ({{$post_array->credits_required}} Points needed)</a></div>
               @elseif(strlen(strip_tags($post_array->post)) > 500)
                  {!! str_limit(strip_tags($post_array->post), $limit = 500, $end = '...') !!}
                  <a href="{{url('blog/'.$post_array->id)}}">Read more</a>
               @else
                  {!! strip_tags($post_array->post) !!}
               @endIf
            </p>
            <div class="row">
            <div class="col-md-12"  style="margin-top:8px;">
                <a  class="btn btn-default btn-xs">{{$post_array->view_count}} views</a>
                @if($post_array->comments->count() == 1)
                    <a href="{{url('blog/'.$post_array->id)}}" class="btn btn-default btn-xs">{{$post_array->comments->count()}} Comment</a>
                @else
                    <a href="{{url('blog/'.$post_array->id)}}" class="btn btn-default btn-xs">{{$post_array->comments->count()}} Comments</a>
                @endif

                @if (Auth::guest())
                    <a class="btn btn-primary btn-xs"  href="{{ url('/login?ref=save') }}">Save</a>
                @else
                    <a style="width:45px;" id="save_link_{{$post_array->id}}"  href="javascript:void(0)" onclick="save({{$post_array->id}})" class="btn btn-primary btn-xs">
                        @if($saved_ids && (in_array($post_array->id, $saved_ids)))
                            Saved
                        @else
                            Save
                        @endif
                    </a>
                @endif

                <a href="" class="btn btn-danger btn-xs">Report</a>
                @if(Auth::user())
                    <a style="width:70px;" id="like_link_{{$post_array->id}}" href="javascript:void(0)" onclick="like({{$post_array->id}})" class="btn btn-xs btn-info">
                        @if($likes && (in_array($post_array->id, $likes)))
                            Liked
                        @else
                            Like
                        @endif
                        ({{$post_array->likes_count}})
                    </a>
                    <a style="width:80px;" id="dislike_link_{{$post_array->id}}" href="javascript:void(0)" onclick="dislike({{$post_array->id}})" class="btn btn-xs btn-info">
                        @if($dislikes && (in_array($post_array->id, $dislikes)))
                            Disliked
                        @else
                            Dislike
                        @endif
                        ({{$post_array->dislikes_count}})
                    </a>
                @endif

            </div>
            </div>
            <hr>
            @endforeach
            <div align="center">
               @if(isset($sword))
               {{$posts->appends(['q' => $sword])->links()}}
               @else
               {{$posts->links()}}
               @endIF
            </div>
         </div>
         @else
            @if (isset($followig_users) && $followig_users==false)
            <div class="infinite-scroll">
              <div style="margin-top:20px;" class="well">
                <p> This page shows posts from authors you follow. Please start to follow authors to get updates. </p>
                <p> Most popular authors are listed below. </p>
              </div>
            </div>
            @else
                <div class="no-records"><i><h5>No records found.</h5></i></div>
            @endif
         @endIf
      </div>
   </div>
</div>

@endsection
