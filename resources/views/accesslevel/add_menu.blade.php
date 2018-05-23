@extends('layouts.app')
@section('content')
<link href="{{ asset('css/icon_picker.css') }}" rel="stylesheet">
<div class="container-fluid">
    <div class="row">
		<div id="togle-sidebar-sec" class="active">
			<!-- Sidebar -->
			<div id="sidebar-togle-sidebar-sec">
			  <div id="sidebar_menu" class="sidebar-nav">
          <ul></ul>
        </div>
			</div>
			  
			<!-- Page content -->
			<div id="page-content-togle-sidebar-sec">
				@if(Session::has('alert-class'))
					<div class="alert alert-success alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
				@elseif(Session::has('flash_message'))
					<div class="alert alert-danger alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
				@endif
				<div class="col-md-12">
					<h3 class="text-center">Manage Menus</h3>
					<div class="panel panel-default">
						<div class="panel-heading">{{isset($detail_edit->id) ? "Edit " : "Add " }} Menu</div>
						<div class="panel-body">
							<form class="form-horizontal form-menu" role="form" method="POST" action="" id ="menuform">
								{{ csrf_field() }}
								<div class="form-group{{ $errors->has('parent_name') ? ' has-error' : '' }}">
									
									@if($parent_id != 0)
									<label for="parent_menu" class="col-md-4 control-label">Parent Menu</label>
									<div class="col-md-6">
										<?php 
										$title = DB::table('menus')->where('id', $parent_id)->first();
										?>
										<select class="form-control parent" id="parent_menu" name="parent_id">
										<option  value="{{$title->parent_id}}">{{$title->title}}</option>
										</select>
									</div>
									@else
									<input type="hidden" name="parent_id" value="0">
									@endif
									
								</div>
								<div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
									<label for="icon" class="col-md-4 control-label">Icon</label>
									<div class="col-md-6">
									<input type="text" name="icon" class="icon-picker" value="{{isset($detail_edit->icon) ? $detail_edit->icon : "" }}" placeholder="Click on icon and then search    ❱ " />
										@if ($errors->has('icon'))
											<span class="help-block">
												<strong>{{ $errors->first('icon') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
									<label for="title" class="col-md-4 control-label">Title</label>
									<div class="col-md-6">
										<input id="title" type="text" class="form-control required" name="title" value="{{isset($detail_edit->title) ? $detail_edit->title : "" }}" autofocus>
										@if ($errors->has('title'))
											<span class="help-block">
												<strong>{{ $errors->first('title') }}</strong>
											</span>
										@endif
									</div>
								</div>
								<div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
									<label for="url" class="col-md-4 control-label">Url</label>
									<div class="col-md-6">
										<input id="url" type="text" class="form-control url" name="url" value="{{isset($detail_edit->url) ? $detail_edit->url : "" }}" autofocus>
										@if ($errors->has('url'))
											<span class="help-block">
												<strong>{{ $errors->first('url') }}</strong>
											</span>
										@endif
									</div>
								</div>
								
								<div id="template-module"></div>
								<div class="form-group row">
                                    <div class="col-md-6">
                                        <a type="button" class="btn btn-default" href="{{ URL('list_menu') }}">
                                        Back
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary pull-right save_button">
											{{isset($detail_edit->id) ? "Save " : "Create " }}
										</button>
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
<script src="{{ URL('/js/icon_picker.js') }}"></script>
<script>
$(function(){
    $("#menuform").validate();   
    $(".icon-picker").iconPicker();   
});
</script>
@endsection

