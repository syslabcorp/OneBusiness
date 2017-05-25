@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		@if(Session::has('flash_message'))
			<div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Enter Your Password</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/password') }}">
                        {{ csrf_field() }}
						<input type="hidden" name="email" value="{{ $email }}" />
						<input type="hidden" name="logintype" value="{{ $logintype }}" />
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" value="{{ old('password') }}" required autofocus>
							</div>
						</div>
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
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
