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
                <h3 class="text-center">Manage Corporations</h3>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of Corporations
                            @if(\Auth::user()->checkAccessById(30, "A"))
                            <a href="{{ URL('add_corporation') }}" class="pull-right">Add Corporation</a>
                            @endif
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="list_corp" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Corp. ID</th>
                                            <th>Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($detail as $key=>$det)
                                            <tr>
                                                <td><span class="dispnone">{{ $det->corp_name }}</span>{{ $det->corp_id }}</td>
                                                <td>{{ $det->corp_name }}</td>
                                                <td><a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(30, 'E') ? '' : 'disabled' }}" data-title="Edit" href="{{ URL::to('add_corporation/' . $det->corp_id) }}" data-toggle="tooltip" data-placement="top" title="Edit Corporation"><span class="glyphicon glyphicon-pencil"></span></a>
                                                <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(30, 'D') ? '' : 'disabled' }}" data-title="Delete" href="#" rel="{{ URL::to('delete_corporation/' . $det->corp_id) }}" id="{{ $det->corp_id }}" corp-name="{{ $det->corp_name }}" data-toggle="tooltip" data-placement="top" title="Delete Corporation"><span class="glyphicon glyphicon-trash"></span></a></td>
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
    $('#list_corp').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        var corp_name = $(this).attr("corp-name");
        var id = $(this).attr("id");
        swal({
            title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete Corporation <strong>"+id+" - "+corp_name +"</strong><br/> Are you sure?</div>",
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


