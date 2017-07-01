@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Custom Login</div>
                <div class="panel-body">
					@if(!isset($btn))
                    <form class="form-horizontal" role="form" method="POST" action="{{ URL::to('/logintype') }}">
                        {{ csrf_field() }}
						<input type="hidden" name="email" value="{{ $email }}" />
						<input type="hidden" name="username" value="{{ $username }}" />
						<div class="form-group">
							<label for="email" class="radio-check-label col-md-4 control-label">Choose Login Type</label>
							<div class="mt-radio-inline">
								<!--label class="mt-radio">
									<input type="radio" name="logintype" id="optionsRadios5" value="pswd_auth"> Password
									<span></span>
								</label-->
								<label class="mt-radio">
									<input type="radio" name="logintype" id="optionsRadios5" value="otp_auth"> OTP
									<span></span>
								</label>
								@if($finger_count)
								<label class="mt-radio">
									<input type="radio" name="logintype" id="optionsRadios4" value="bio_auth"> Biometric
									<span></span>
								</label>
								@else
								<label class="mt-radio">
									<input type="radio" name="logintype" id="optionsRadios4" value="pswd_auth"> Biometric
									<span></span>
								</label>
								@endif
							</div>
						</div>
						<div class="form-group">
                            <div class="col-md-8 col-md-offset-4 submit-button">
                                
                            </div>
                        </div>
				   </form>
				   @else
					   <div class="form-group">
                            <div class="col-md-8 col-md-offset-4 submit-button">
								<?php echo $btn; ?>
                            </div>
                        </div>
				   @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(function(){
		$("input[name='logintype']").click(function(){
			var username= $("input[name='username']").val();
			var type = $(this).val();
			check_for_btn(username, type);
		});
		var username= $("input[name='username']").val();
		var type = $("input[name='logintype']:checked").val();
		if(type !== undefined){
			check_for_btn(username, type); 
		}	
	});
</script>

@endsection
