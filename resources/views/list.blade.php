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

         @if(count($posts))
      <div class="infinite-scroll">
            @foreach($posts as $post_array)
            <h3><a href="{{url('blog/'.$post_array->id)}}">{{$post_array->title}}</a></h3>
            <p><span class="glyphicon glyphicon-time"></span> <i>Created on {{date('F d, Y',strtotime($post_array->created_at))}} by <a href="{{url('blog/user/'.$post_array->user->id)}}">{{$post_array->user->name}}</a> <a class="btn btn-default btn-xs" href="">Follow</a> </i></p>
            <p>
               @if($post_array->is_locked)
                  {{$post_array->introduction}}
                  <div align="center"><a href="" class="btn btn-primary">Unlock (40 Credits)</a></div>
               @elseif(strlen($post_array->post) > 500)
                  {!! nl2br(e(str_limit($post_array->post, $limit = 500, $end = '...'))) !!}
                  <a href="{{url('blog/'.$post_array->id)}}">Read more</a>
               @else
                  {!! nl2br(e($post_array->post)) !!}
               @endIf
            </p>
            <div class="row">                
                  <div class="col-md-12"  style="margin-top:8px;">
                            <a href="" class="btn btn-default btn-xs">145 views</a>
                            <a href="" class="btn btn-primary btn-xs">Save</a>
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
            <div class="no-records"><i><h5>No records found.</h5></i></div>
         @endIf
      </div>
   </div>
</div>

@endsection
