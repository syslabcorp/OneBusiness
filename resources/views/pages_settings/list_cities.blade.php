@extends('layouts.app')

@section('content')

<h3 class="text-center">Manage Locations</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-2">
    <!--menu here-->
        </div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">List of Cities

                @if(\Auth::user()->checkAccess("Locations", "A"))
                    <!--a href="{{ URL('add_city/') }}" class="pull-right">Add City</a-->
				<a href="{{ URL('add_city'.(($prov_id) ? ('/0/'.$prov_id) : '' )) }}" class="pull-right">Add City</a>
                @endif

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
                                
									<a href="{{ URL::to('add_city/'.$det->City_ID.(($prov_id) ? ('/'.$prov_id) : '' )) }}" class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccess('Locations', 'E') ? '' : 'disabled' }}" data-title="View" data-toggle="tooltip" data-placement="top" title="Edit City"><span class="glyphicon glyphicon-pencil"></span></a>

                                    <!--a class="btn btn-danger btn-md blue-tooltip {{ \Auth::user()->checkAccess('Locations', 'D') ? '' : 'disabled' }}" data-title="Delete"  data-toggle="tooltip" data-placement="top" title="Delete City"><span class="glyphicon glyphicon-trash"></span></a-->
									 <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccess('Locations', 'D') ? '' : 'disabled' }}"
									 data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_city/' . $det->City_ID) }}" data-toggle="tooltip" data-placement="top" title="Delete City"><span class="glyphicon glyphicon-trash"></span></a></td>
                                    
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
            </div>
        </div>
    </div>
</div>
<script>
 $('[data-toggle="tooltip"]').tooltip();
 
$(document).ready(function() {
    $('#list_city').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Featured Data!",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: "No",
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

