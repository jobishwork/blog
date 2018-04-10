@extends('layouts.app')
@section('content')
<div class="container">
   <div class="row">
      <!-- Blog Post Content Column -->
      <div class="col-md-3 right-sidebar">
         @include('sidebar')
      </div>
      <div class="col-lg-8">
         <h3><a href="">Send Message to {{$user->name}}</a></h3>
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
          <p class="alert alert-success">{{ Session::get('message') }}</p>
         @endif
         <form name="form1" method="POST" action="{{url('message/store/'.$user->id)}}">
            {{ csrf_field() }}
            <div class="form-group">
               <label for="subject">Subject</label>
               <input type="text" autofocus="" placeholder="Enter Subject" name="subject" class="form-control" value="{{old('subject')}}" id="title">
            </div>

            <div class="form-group">
               <label for="message">Message</label>
               <textarea rows="8" name="message" class="form-control">{{old('message')}}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
         </form>
      </div>
      <!-- Blog Sidebar Widgets Column -->

   </div>
</div>
</div>
