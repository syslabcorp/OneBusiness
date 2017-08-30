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
                    <h3 class="text-center">Manage Features</h3>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            @if(!isset($module_id))
                                List of Features 
                            @else
                                @foreach($detailfeature as $key=>$detailfet)
                                    <?php $desc = $detailfet->description; ?>
                                @endforeach
                                    <a href="{{ URL('list_module') }}" >{{ $desc }}</a>
                            @endif   
                            <a href="{{ URL('add_feature'.(($module_id) ? ('/0/'.$module_id) : '' )) }}" class="pull-right">Add Feature</a></div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="list_featur" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Feature ID</th>
                                                @if(!isset($module_id))
                                                    <th>Module Description</th>
                                                @endif
                                                <th>Feature</th>
                                                <th>Action</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($detailfeature as $key=>$detail)
                                                <tr>
                                                    <td>{{ $detail->feature_id }}</td>
                                                    @if(!isset($module_id))
                                                        <td>{{ $detail->description }}</td>
                                                    @endif
                                                    <td>{{ $detail->feature }}</td>
                                                    <td><a class="btn btn-primary btn-md blue-tooltip" data-title="Edit" href="{{ URL::to('add_feature/' . $detail->feature_id.(($module_id) ? ('/'.$module_id) : '' )) }}" data-toggle="tooltip" data-placement="top" title="Edit Feature"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a class="btn btn-danger btn-md sweet-4 red-tooltip" data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_feature/' . $detail->feature_id.(($module_id) ? ('/'.$module_id) : '' )) }}" data-toggle="tooltip" data-placement="top" title="Delete Feature"><span class="glyphicon glyphicon-trash"></span></a></td>
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
    $('#list_featur').DataTable();
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




