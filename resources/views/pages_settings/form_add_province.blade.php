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
				<!--page content-->
				<div id="page-content-togle-sidebar-sec">
                    @if(Session::has('alert-class'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @elseif(Session::has('flash_message'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Province</h3>
                        <div class="row">
                           	<div class="panel panel-default">
								<div class="panel-heading">
						{{isset($detail_edit->Prov_ID)? "Edit Province: " : "Add Province" }} {{isset($detail_edit->Prov_ID)? $detail_edit->Province : "" }}
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="POST" action="" id ="Provinceform">
									{{ csrf_field() }}
									<div class="form-group{{ $errors->has('parent_prov') ? ' has-error' : '' }}">
										<label for="Province" class="col-md-4 control-label">Province</label>
										<div class="col-md-6">
											<input id="Province_name" type="text" class="form-control required" name="Province_name"  value="{{isset($detail_edit->Province) ? $detail_edit->Province : "" }}" autofocus>
											@if ($errors->has('Province_name'))
												<span class="help-block">
													<strong>{{ $errors->first('Province_name') }}</strong>
												</span>
											@endif
										</div>
									</div>
									<div class="form-group">
											<div class="row">
														<div class="col-sm-6">
															<a href="{{ url('/list_provinces') }}" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</a>
														</div>
														<div class="col-sm-6">
															{!! csrf_field() !!}
															<button type="submit" class="btn btn-success pull-right">   {{isset($detail_edit->Prov_ID) ? "Save " : "Create " }}</button>
														</div>
											</div>
									</div>
							</form>
						</div>
							</div>
                        </div>
                    </div>
                </div>
						
      </div>
	</div>
</div>
@endsection