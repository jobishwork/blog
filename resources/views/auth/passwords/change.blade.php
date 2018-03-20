@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Change Password</div>
				<div class="panel-body">
					@if(Session::has('success'))
						<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{!! Session::get('success') !!}</p>
					@endif
					@if(Session::has('error_message'))
						<p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{!! Session::get('error_message') !!}</p>
					@endif
					@if ($errors->any())
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
					@endif
					<form class="form-horizontal" method="POST" action="{{ url('/password/change') }}">
						{{ csrf_field() }}
						<div class="form-group{{ $errors->has('current_password') ? ' has-error' : '' }}">
							<label for="current_password" class="col-md-4 control-label">Current Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="current_password" autofocus="">
							</div>
						</div>
						<div class="form-group{{ $errors->has('password')||$errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">New Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password">
							</div>
						</div>
						<div class="form-group{{ $errors->has('password')||$errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label class="col-md-4 control-label">Confirm New Password</label>
							<div class="col-md-6">
								<input type="password" class="form-control" name="password_confirmation">
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-md-offset-4">
								<button type="submit" class="btn btn-primary">
								Change Password
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
