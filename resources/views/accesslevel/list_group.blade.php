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
                <h3 class="text-center">Manage Groups</h3>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Groups
                            @if(\Auth::user()->checkAccessById(14, "A")) 
                            <a href="{{ URL('list_group/add_group') }}" class="pull-right">Add Group</a>
                            @endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="list_group" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Group No.</th>
                                            <th>Group Description</th>
                                            <th class="text-center">Active</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($group_detail as $key=>$det)
                                            <tr>
                                                <td><span class="dispnone">{{ $det->desc }}</span>{{ $det->group_ID }}</td>
                                                <td>{{ $det->desc }}</td>
                                                <td class="text-center" >
                                                    <input id="active_grp" class="active_group {{ \Auth::user()->checkAccessById(14, 'E') ? '' : 'disabled' }}" type="checkbox" group-id="{{ $det->group_ID }}" name="active_group" {{isset($det->status) && $det->status == 1 ? "checked" : "" }} disabled >
                                                </td>

                                                <td><a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(14, 'E') ? '' : 'disabled' }}" data-title="Edit" href="{{ URL::to('list_group/add_group/' . $det->group_ID) }}" data-toggle="tooltip" data-placement="top" title="Edit Group"><span class="fas fa-pencil-alt"></span></a>
                                                <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(14, 'D') ? '' : 'disabled' }}" data-title="Delete" href="#" rel="{{ URL::to('delete_group/' . $det->group_ID) }}" data-toggle="tooltip" data-placement="top" title="Delete Group" group-name="{{ $det->desc }}" id="{{ $det->group_ID }}"><span class="far fa-trash-alt"></span></a></td>
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
        var group_name = $(this).attr("group-name");
        var id = $(this).attr("id");
        swal({
            title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete Group <strong>"+id+" - "+group_name +"</strong><br/> Are you sure?</div>",
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


