@extends('layouts.app')

@section('content')
<h3 class="text-center">Manage Modules</h3>
<div class="row">
    @if(Session::has('alert-class'))
        <div class="alert alert-success col-md-8 col-md-offset-2"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
    @elseif(Session::has('flash_message'))
        <div class="alert alert-danger col-md-8 col-md-offset-2"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
    @endif
</div>
<div class="container-fluid">
    <div class="row">
		<div class="col-md-2 col-xs-12">
			<div id="treeview_json"></div>
		</div>
        <div class="col-md-8 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">List of Modules<a href="{{ URL('add_module') }}" class="pull-right">Add Module</a></div>
                <div class="panel-body">
                <div class="table-responsive">
                   <table id="list_modul" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SNo.</th>
                                <th>Corporation Name</th>
                                <th>Module Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detail as $key=>$det)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $det->corp_name }}</td>
                                    <td>{{ $det->description }}</td>
                                    <td>
										<a class="btn btn-success btn-md blue-tooltip" data-title="Edit" href="{{ URL::to('list_feature/' . $det->module_id) }}" data-toggle="tooltip" data-placement="top" title="List Features"><span class="glyphicon glyphicon-eye-open"></span></a>
										<a class="btn btn-primary btn-md blue-tooltip" data-title="Edit" href="{{ URL::to('add_module/' . $det->module_id) }}" data-toggle="tooltip" data-placement="top" title="Edit Module"><span class="glyphicon glyphicon-pencil"></span></a>
										<a class="btn btn-danger btn-md sweet-4 red-tooltip" data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_module/' . $det->module_id) }}" data-toggle="tooltip" data-placement="top" title="Delete Module"><span class="glyphicon glyphicon-trash"></span></a></td>
                                </tr>  
                            @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#list_modul').DataTable();
    $('.sweet-4').click(function(){
        var delete_url = $(this).attr("rel");
        swal({
            title: "Are you sure?",
            text: "You will not be able to recover this Module Data!",
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



