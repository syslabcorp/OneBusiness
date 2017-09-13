@extends('layouts.custom')

@section('content')
 <!-- Page content -->
            <div id="page-content-togle-sidebar-sec">
                @if(Session::has('alert-class'))
                    <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                @elseif(Session::has('flash_message'))
                    <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                @endif
                <div class="col-md-12 col-xs-12">
                    <h3 class="text-center">Manage Locations</h3>
                    <div class="row">  
                      
                    </div>
                </div>
            </div>
<div class="container-fluid">
    <div class="row">
				<div class="panel panel-default">
						<div class="panel-heading"> List of Provinces 

						@if(\Auth::user()->checkAccessById(18, "A"))
							<a href="{{ URL('add_province') }}" class="pull-right">Add Province</a>
						@endif 
						</div>
						<div class="panel-body">
								 <table id="list_modul" class="table table-striped table-bordered" cellspacing="0" width="100%">
								<thead>
									<tr>
										<th>SNo.</th>
										<th>Province</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<!--tr>
										<td> cities here</td>
									</tr-->
									   @foreach($provs as $key=>$det)
										<tr>
											<td>{{ ++$key }}</td>
											<td>{{ $det->Province}}</td>
											<td>
											<a href="{{ URL::to('view_cities/'.$det->Prov_ID) }}" class="btn btn-success btn-md blue-tooltip" data-title="View" data-toggle="tooltip" data-placement="top" title="View Province"><span class="glyphicon glyphicon-eye-open"></span></a>

											<a href="{{ URL('add_province/'.$det->Prov_ID) }}" class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(18, 'E') ? '' : 'disabled' }}"  data-title="Edit" data-toggle="tooltip" data-placement="top" title="Edit Province"><span class="glyphicon glyphicon-pencil"></span></a>
										   </td>

										</tr>  
										@endforeach
								</tbody>
							</table>
						</div>
				</div>
    </div>
</div>

@endsection

