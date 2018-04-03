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
            $following_ids = [];
        if(Auth::user())
            $following_ids = Auth::user()->following->pluck('id')->toArray();
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
                        @if($following_ids && in_array($post_array->user->id, $following_ids))
                        <a class="btn btn-default btn-xs" href="{{ url('following/'.$post_array->user->id) }}">
                            Following
                        </a>
                        @else
                        <a class="btn btn-default btn-xs" href="{{ url('following/'.$post_array->user->id) }}">
                            Follow
                        </a>
                        @endif
                    @endif

                </i>
            </p>
            <p>
               @if($post_array->is_locked)
                  {!! str_limit($post_array->post, $limit = 500, $end = '...') !!}
                  <div align="center"><a href="" class="btn btn-primary">Unlock (40 Credits)</a></div>
               @elseif(strlen($post_array->post) > 500)
                  {!! str_limit($post_array->post, $limit = 500, $end = '...') !!}
                  <a href="{{url('blog/'.$post_array->id)}}">Read more</a>
               @else
                  {!! $post_array->post !!}
               @endIf
            </p>
            <div class="row">
            <div class="col-md-12"  style="margin-top:8px;">
                <a href="" class="btn btn-default btn-xs">{{$post_array->view_count}} views</a>
                @if($post_array->comments->count() == 1)
                    <a href="{{url('blog/'.$post_array->id)}}" class="btn btn-default btn-xs">{{$post_array->comments->count()}} Comment</a>
                @else
                    <a href="{{url('blog/'.$post_array->id)}}" class="btn btn-default btn-xs">{{$post_array->comments->count()}} Comments</a>
                @endif

                @if (Auth::guest())
                    <a class="btn btn-primary btn-xs"  href="{{ url('/login?ref=save') }}">Save</a>
                @else
                    <a href="{{ url('saveArticle/'.$post_array->id) }}" class="btn btn-primary btn-xs">
                        @if($saved_ids && (in_array($post_array->id, $saved_ids)))
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
