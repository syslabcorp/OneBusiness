@extends('layouts.custom')

@section('content')

<h3 class="text-center">Manage Locations</h3>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">List of Cities

                @if(\Auth::user()->checkAccessById(18, "A"))
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
                                
									<a href="{{ URL::to('add_city/'.$det->City_ID.(($prov_id) ? ('/'.$prov_id) : '' )) }}" class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(18, 'E') ? '' : 'disabled' }}" data-title="View" data-toggle="tooltip" data-placement="top" title="Edit City"><span class="glyphicon glyphicon-pencil"></span></a>
									
									 <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(18, 'D') ? '' : 'disabled' }}"" data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_city/'.$det->City_ID.(($prov_id) ? ('/'.$prov_id) : '' ))  }}" data-toggle="tooltip" data-placement="top" title="Delete City"><span class="glyphicon glyphicon-trash"></span></a></td>
									 
									 </td>
                                    
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

   <!-- Modal delete item from inventory -->
    <div class="modal fade" id="confirm-delete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" >
                    <div class="modal-body">
                        <p>You are about to delete one track, this procedure is irreversible.</p>
                        <p>Do you want to proceed deleting <span style="font-weight: bold" class="itemToDelete"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input style="display: none" class="serviceId" >
                        {!! csrf_field() !!}
                        {{ method_field('Delete') }}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-ok" class="deleteItem">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>

$(document).ready(function() {
	$('[data-toggle="tooltip"]').tooltip();
    $('#list_city').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this data..",
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
   

});
</script>

@endsection
