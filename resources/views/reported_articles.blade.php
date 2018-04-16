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
      <h3><a>Reported Articles</a></h3>
      @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table">
        <thead>
          <tr>
            <th width="70%">Title</th>
            <th>Count</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        @if(count($reported_articles))
          @foreach($reported_articles as $reported_article)
            <tr >
                <td>
                    <a href="{{url('blog/'.$reported_article->post->id)}}">{{$reported_article->post->title}}</a>
                </td>
                <td>{{$reported_article->count}}</td>
                <td>
                    <a style="width:75px;" href="{{url('suspend-article/'.$reported_article->post->id)}}" class="btn btn-xs btn-warning">
                        @if($suspended_ids && (in_array($reported_article->post->id, $suspended_ids)))
                            Suspended
                        @else
                            Suspend
                        @endif
                    </a>
                </td>

            </tr>
          @endForeach
        @else
            <tr>
              <td align="center"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="3">{{$reported_articles->links()}} </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
