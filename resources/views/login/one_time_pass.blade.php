@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		@if(Session::has('flash_message'))
			<div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Enter OTP</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/one_time_pass') }}">
                        {{ csrf_field() }}
						<input type="hidden" name="user_id" value="{{ $user_id }}" />
						<input type="hidden" name="is_forgot" value="{{ isset($forgot_pass) ? $forgot_pass : 0 }}" />
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Enter OTP</label>
                            <div class="col-md-6">
                                <input id="otp" type="text" class="form-control" name="otp" value="{{ old('email') }}" required autofocus>
							</div>
						</div>
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
								@if(isset($forgot_pass))
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
								@else
								<button type="submit" class="btn btn-success">
                                    Login
                                </button>	
								@endif
                            </div>
                        </div>
				   </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
