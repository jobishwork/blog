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
      <h3><a href="">Sent Messages</a></h3>
      @if(Session::has('message'))
        <p class="alert alert-warning">{{ Session::get('message') }}</p>
      @endif
      <table class="table table-hover">
        <thead>
          <tr>
            <th>Receiver</th>
            <th>Subject</th>
            <th>Received Date</th>
          </tr>
        </thead>
        <tbody>
        @if(count($sent_messages))
         @foreach($sent_messages as $sent_message)
              <tr>
                <td><a href="{{ url('sent_message_show/'.$sent_message->id) }}">{{$sent_message->receiver->name}}</a></td>
                <td><a href="{{ url('sent_message_show/'.$sent_message->id) }}">{{$sent_message->subject}}</a></td>
                <td><a href="{{ url('sent_message_show/'.$sent_message->id) }}">{{date('d M Y',strtotime($sent_message->created_at))}}</a></td>

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
