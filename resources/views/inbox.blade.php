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
      <h3><a href="">My Articles</a></h3>
      @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Subject</th>
            <th>Message</th>
            <th>Received Date</th>
          </tr>
        </thead>
        <tbody>
        @if(count($inboxes))
         @foreach($inboxes->receivedMessages as $inbox)
              <tr>
                <td>{{$inbox->subject}}</td>

                  <td>{{$inbox->sender->name}}</td>

                <td>{{date('d M Y',strtotime($inbox->created_at))}}</td>

              </tr>
              @endforeach

        @else
            <tr>
              <td align="center"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="3"></td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
