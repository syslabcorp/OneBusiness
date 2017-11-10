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
                <div class="col-md-12 col-xs-12">
                    <h3 class="text-center">Manage Templates</h3>
                    <div class="row">  
                        <div class="panel panel-default">
                            <div class="panel-heading">List of Templates
                                @if(\Auth::user()->checkAccessById(11, "A"))
                                <a href="{{ URL('add_template') }}" class="pull-right">Add Template</a></div>
                                @endif
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="list_templat" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Temp. ID</th>
                                                <th>Template Description</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($listtemp as $key=>$list)
                                                <tr>
                                                    <td>{{ $list->template_id }}</td>
                                                    <td>{{ $list->description }}</td>
                                                    <td><a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(11, 'E') ? '' : 'disabled' }}" data-title="Edit" href="{{ URL::to('add_template/' . $list->template_id) }}" data-toggle="tooltip" data-placement="top" title="Edit Template"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(11, 'D') ? '' : 'disabled' }}" data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_template/' . $list->template_id) }}" data-toggle="tooltip" data-placement="top" title="Delete Template" template-name="{{ $list->description }}" id="{{ $list->template_id }}"><span class="glyphicon glyphicon-trash"></span></a></td>
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
    $('#list_templat').DataTable();
     $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        var template_name = $(this).attr("template-name");
        var id = $(this).attr("id");
        swal({
            title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete Template <strong>"+id+" - "+template_name +"</strong><br/> Are you sure?</div>",
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





