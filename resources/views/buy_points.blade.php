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
        <div class="col-lg-7">
            <h3><a href="">Buy Points</a></h3>
        </div>
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
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table">
        <thead>
          <tr>
            <td colspan="3">
            <form name="form1" method="POST" action="{{url('/transactions/add/'.$user->id)}}">
                {{ csrf_field() }}
            <div class="col-lg-2">
               <h3> <input type="text" style="margin-top:10" class="form-control" placeholder="Enter Points" name="buy_points""></h3>
            </div>
            <div class="col-lg-2">
                <h3><button type="submit" class="btn btn-primary">Buy Points</button></h3>
            </div>
          </form>
            </td>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
