@extends('layouts.app')
@section('content')
<div class="container">
  <div class="row">
    <!-- Blog Sidebar Widgets Column -->
    <div class="col-md-3 right-sidebar">
      @include('sidebar')
    </div>
    @php
    $user = Auth::user();
    @endphp
    <!-- Blog Post Content Column -->
    <div class="col-lg-9">
        @if(Session::has('message'))
            <p class="alert alert-warning">{{ Session::get('message') }}</p>
        @endif
        <div class="col-lg-7">
            <h3><a href="">Unlock Article</a></h3>
        </div>
        <hr>
        <div>
            <a align:right class="btn btn-info pull-right" href="{{ url('points/create') }}">Buy Points</a>
        </div>
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                   @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
                   @endforeach
                </ul>
            </div>
        @endif

        <form name="form1" method="POST" action="{{url('unlock/article/'.$post->id)}}">
            {{ csrf_field() }}
            <div class="col-lg-7">
                <h4>Points required to unlock the Article {{$post->credits_required}}</h4>
            </div>
            <div class="col-lg-10 " align="center">
                <h3><button type="submit" class="btn btn-lg btn-primary">Unlock</button></h3>
            </div>
        </form>
    </div>
  </div>
</div>
</div>
@endsection


