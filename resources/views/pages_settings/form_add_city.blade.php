@extends('layouts.app')

@section('content')
  <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
					 <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                  <div id="sidebar_menu" class="sidebar-nav">
                    <ul></ul>
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
                        <h3 class="text-center">Cities</h3>
                        <div class="row">
                           	<div class="panel panel-default">
									<div class="panel-heading">
									{{isset($detail_edit_city->City_ID)? "Edit City: " : "Add City" }}
									{{isset($detail_edit_city->City_ID)? $detail_edit_city->City:"" }}
									</div>
						<div class="panel-body">
						<form class="form-horizontal form" role="form" method="POST" action="" id ="cityform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('City') ? ' has-error' : '' }}">
                            <label for="province_name" class="col-md-4 control-label">Province: </label>
								<div class="col-md-6"> 
                         
								<select class="form-control required" id="prov_id" name="Prov_ID">
                                    <option value="">Choose Province</option>					
									
                                        @foreach ($province as $prov) 
                                            <option {{ (isset($detail_edit_city->Prov_ID) && 
											($detail_edit_city->Prov_ID == $prov ->Prov_ID)) ? "selected" : "" }} 
											value="{{ $prov ->Prov_ID }}">{{$prov->Province }}</option>
                                          
                                        @endforeach
                                </select>
								</div>
						</div>
                        <div class="form-group{{ $errors->has('city_name') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">City Name:</label>
                            <div class="col-md-6">
                                <input id="city_name" type="text" class="form-control required" name="city_name"  value="{{isset($detail_edit_city->City) ? $detail_edit_city->City : "" }}" autofocus>
                                
                            </div>
                        </div>
                        <div class="form-group">
						 <div class="row">
									<div class="col-sm-6">
                                        <a href="{{ URL::to('view_cities/'.$detail_edit_city->Prov_ID) }}" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</a>
                                    </div>
                                    <div class="col-sm-6">
                                       {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-success pull-right">    {{isset($detail_edit_city->City_ID) ? "Save " : "Create " }}</button>
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