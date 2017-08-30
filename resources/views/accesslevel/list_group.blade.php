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
            @if(Session::has('alert-class'))
                <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
            @elseif(Session::has('flash_message'))
                <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
            @endif
            <div class="col-md-12">
                <h3 class="text-center">Manage Groups</h3>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Groups<a href="{{ URL('add_group') }}" class="pull-right">Add Group</a></div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="list_group" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Group No.</th>
                                            <th>Group Description</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group_detail as $key=>$det)
                                            <tr>
                                                <td>{{ $det->group_ID }}</td>
                                                <td>{{ $det->desc }}</td>
                                                <td>
                                                    <input id="active_grp" class="active_group" type="checkbox" group-id="{{ $det->group_ID }}" name="active_group" {{isset($det->status) && $det->status == 1 ? "checked" : "" }} >
                                                </td>

                                                <td><a class="btn btn-primary btn-md blue-tooltip" data-title="Edit" href="{{ URL::to('add_group/' . $det->group_ID) }}" data-toggle="tooltip" data-placement="top" title="Edit Group"><span class="glyphicon glyphicon-pencil"></span></a>
                                                <a class="btn btn-danger btn-md sweet-4 red-tooltip" data-title="Delete" href="#" rel="{{ URL::to('delete_group/' . $det->group_ID) }}" data-toggle="tooltip" data-placement="top" title="Delete Group"><span class="glyphicon glyphicon-trash"></span></a></td>
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
    </div>
</div>
</div>
<script>
$(document).ready(function() {
    $('#list_group').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        swal({
            title: "Are you sure?",
            text:  "You will not be able to recover this Group Data!",
            type:  "warning",
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
    var _token = $("meta[name='csrf-token']").attr("content");
    var formname = "list_group";
    $(document).on("click", ".active_group", function(){
        var id  = $(this).attr('group-id');
        if ($(this).is(':checked')) {
            var activevalue = 1;
        }else{
            var activevalue = 0;
        }
        $.ajax({
            url: ajax_url+'/update_active_group',
            type: "POST",
            data: {_token,id,activevalue},
            success: function(response){
              location.href = ajax_url + "/" + formname;  
            }
        });
    });
});
</script>
@endsection


