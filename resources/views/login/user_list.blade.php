@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
		@if(Session::has('alert-class'))
			<div class="alert alert-success"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@elseif(Session::has('flash_message'))
			<div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">Business One Users List</div>
                <div class="panel-body">
						<div class='row'>
							<div class='col-md-12'>
								<table class='table table-bordered table-hover'>
									<thead>
										<tr>
											<th class='col-md-4'>S. No.</th>
											<th class='col-md-4'>Username</th>
											<th class='dispnone col-md-2'>Template</th>
											<th class='col-md-2'>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php $base_path = URL('/biomertic-login');  foreach($users AS $k=>$user){ ?>
										<tr class="row-<?php echo $user['user_id']; ?>">
											<td><?php echo ++$k; ?></td>
											<td><?php echo $user['user_name']; ?></td>
											<td class="dispnone"><code id='user_finger_<?php echo $user['user_id']; ?>'>1</code></td>
											<td class="switch-reset-register">
												@if($user['finger_count'])
													<a href='javascript:;' class='btn btn-xs btn-danger' onclick="reset_finger('<?php echo $user['user_id']; ?>')">Reset</a>
												@else
													<?php $url_register = base64_encode($base_path."/register.php?user_id=".$user['user_id']); ?>
													<a href='finspot:FingerspotReg;<?php echo $url_register; ?>' class='user-finger btn btn-xs btn-primary' onclick="user_register_admin('<?php echo $user['user_id']; ?>','<?php echo $user['user_name']; ?>')" finger-count = 0>Register</a>
												@endif
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
            </div>
        </div>
    </div>
</div>
<style>
.modal-dialog {z-index: 9999;}
</style>
<!-- Modal -->
<div id="successReg" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Registration Status</h4>
      </div>
      <div class="modal-body">
        <p>User registration success.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
		<h4 class="modal-title">Registration Status</h4>
      </div>
      <div class="modal-body">
        <p>User registration fail.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection