@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		@if(Session::has('alert-class'))
			<div class="alert alert-success"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@elseif(Session::has('flash_message'))
			<div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Change Password</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/change_pass') }}" id="form-change-password">
                        {{ csrf_field() }}
						<input type="hidden" name="user_id" value="{{ $user_id }}" />
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required autofocus>
							</div>
						</div>
						<div class="form-group{{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                            <label for="confirm_password" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="confirm_password" type="password" class="form-control" name="confirm_password" required>
							</div>
						</div>
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
								<button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
				   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
