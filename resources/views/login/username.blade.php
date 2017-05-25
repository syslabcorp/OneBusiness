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
                <div class="panel-heading">Enter User ID to Login</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/username') }}" id="form-username">
                        {{ csrf_field() }}
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">User ID</label>
                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
							</div>
						</div>
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-4 submit-button">
								<button type="submit" class="btn btn-primary">Submit</button>
								<div class="pull-right forgot-password">
									<a href="{{ URL::to('/forgot_pass') }}">Forgot Your Password</a>
								</div>
                            </div>
                        </div>
				   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
