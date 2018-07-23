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
          
      <!-- Page content -->
      <div id="page-content-togle-sidebar-sec">
		@if(Session::has('alert-class'))
			<div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@elseif(Session::has('flash_message'))
			<div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
             <div class="col-md-12">
			 <h3 class="text-center">Manage Locations</h3>
		<div class="row">    
         <div class="panel panel-default">
                                <div class="panel-heading"> 
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
										List of Cities
                                        </div>
                                        <div class="col-md-6 col-xs-6 text-right">
                                            	@if(\Auth::user()->checkAccessById(18, "A"))<a href="{{ URL('provinces/add_city'.(($prov_id) ? ('/0/'.$prov_id) : '' )) }}" class="pull-right">Add City</a>
												@endif 
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
										@if(count($cities))
										<table id="list_city" class="table table-striped table-bordered" cellspacing="0" width="100%">
											<thead>
												<tr>
													<th>SNo.</th>
													<th>City</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody>
												@foreach($cities as $key=>$det)
												<tr>
													<td>{{ ++$key }}</td>
													<td>{{ $det->City}}</td>
													<td>
													
													<a href="{{ URL::to('provinces/add_city/'.$det->City_ID.(($prov_id) ? ('/'.$prov_id) : '' )) }}" class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(18, 'E') ? '' : 'disabled' }}" data-title="View" data-toggle="tooltip" data-placement="top" title="Edit City"><span class="fas fa-pencil-alt"></span></a>
														
													<a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(18, 'D') ? '' : 'disabled' }}"" data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_city/'.$det->City_ID.(($prov_id) ? ('/'.$prov_id) : '' ))  }}" data-toggle="tooltip" data-placement="top"  city-name="{{ $det->City }}" id="{{ $det->City_ID }}" title="Delete City" ><span class="far fa-trash-alt"></span></a>
													</td>
												</tr>  
													@endforeach
											</tbody>
										</table>
									@else
									 <div class="error">
									{{ __('No data to display') }}
									</div>
								@endif
                                </div>
								<div class="form-group">
									<div class="row">
										<div class="col-sm-6">
											<a href="{{ URL::to('provinces')}}" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</a>
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


<script>
$(document).ready(function() {
    $('#list_templat').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
		 var city_name = $(this).attr("city-name");
        var id = $(this).attr("id");
        swal({
              title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete City <strong>"+id+" - "+city_name +"</strong><br/> Are you sure?</div>",
            html:  true,
            customClass: 'swal-wide',
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Delete',
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm){
            window.location.replace(delete_url);
          } else {
            return false;
          }
        });
    });
    $('[data-toggle="tooltip"]').tooltip();   
});
</script>

@endsection