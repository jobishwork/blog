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
            <h3><a href="">Sent Message</a></h3>
            @if(Session::has('message'))
            <p class="alert alert-warning">{{ Session::get('message') }}</p>
            @endif
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            {{$message->subject}}
                            <span style="float:right">{{date('d M Y',strtotime($message->created_at))}}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr >
                        <td><p>{{$message->message}}</p></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
