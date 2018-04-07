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
            <h3><a href="">{{$user->name}}'s Transactions</a></h3>
        </div>
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
               <h3> <input type="text" style="margin-top:10" class="form-control" placeholder="Add Points" name="add_points""></h3>
            </div>
            <div class="col-lg-2">
                <h3><button type="submit" class="btn btn-primary">Add Points</button></h3>
            </div>
          </form>              
            </td>
          </tr>
          <tr>
            <th>Date</th>
            <th>Credit</th>
            <th>Debit</th>
            <th>Balance</th>
          </tr>
        </thead>
        <tbody>
        @if(count($transactions))
          @foreach($transactions as $transaction)
            <tr >
                <td>{{date('d M Y h:i A',strtotime($transaction->created_at))}}</td>
                <td>{{$transaction->credits}}</td>
                <td>{{$transaction->debits}}</td>
                <td>{{$transaction->balance}}</td>
            </tr>
          @endForeach
        @else
            <tr>
              <td align="center" colspan="4"><i>No records found.</i></td>
            </tr>
        @endIF
        <tr>
           <td align="center" colspan="4">{{$transactions->links()}} </td>
        </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
@endsection
