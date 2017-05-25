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
                <div class="panel-heading">Enter User ID to Forgot Password</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/forgot_pass') }}" id="form-forgot-password">
                        {{ csrf_field() }}
						<div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">User ID</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
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
