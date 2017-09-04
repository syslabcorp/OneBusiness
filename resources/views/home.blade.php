@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div id="togle-sidebar-sec" class="active">
      
      <!-- Sidebar -->
       <div id="sidebar-togle-sidebar-sec">
      <ul id="sidebar_menu" class="sidebar-nav">
           <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
      </ul>
        <div class="sidebar-nav" id="sidebar">     
          <div id="treeview_json"></div>
        </div>
      </div>
          
      <!-- Page content -->
      <div id="page-content-togle-sidebar-sec">
		<div class="col-md-12">
			<div class="panel panel-default margin-top-20">
				<div class="panel-body">
					<div class="col-md-12">
						<div class="row">
							You are logged in!
							<div>
								<div>
									<br/>
									@if($btn != '')
										<div class="row removeonsuccess">
											<input type="hidden" name="_token" value="{{ csrf_token() }}" />
											<label class="col-md-6">Click to register your fingerprint:</label>
											<div class="col-md-6">
												<?php echo $btn; ?>
											</div>
										</div>
									@endif
								</div>
							</div>
						</div>
					</div>
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
