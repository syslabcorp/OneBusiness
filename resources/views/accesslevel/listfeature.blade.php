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
                <div class="col-md-12 col-xs-12">
                    <h3 class="text-center">Manage Features</h3>
                    <div class="row">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                            @if(!isset($module_id))
                                List of Features 
                            @else
                                <?php $desc = $module_desc->description; ?>
                                    <a href="{{ URL('list_module') }}" >{{ $desc }}</a>
                            @endif  
                            @if(\Auth::user()->checkAccessById(12, "A")) 
                            <a href="{{ URL('add_feature'.(($module_id) ? ('/0/'.$module_id) : '' )) }}" class="pull-right">Add Feature</a>
                            @endif
                            </div>
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
                                                    <td><span class="dispnone">{{ isset($detail->description) ? $detail->description : 'System Module' }} {{ $detail->feature }}</span>{{ $detail->feature_id }}</td>
                                                    @if(!isset($module_id))
                                                        <td>{{ isset($detail->description) ? $detail->description : 'System Module' }}</td>
                                                    @endif
                                                    <td>{{ $detail->feature }}</td>
                                                    <td><a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(12, 'E') ? '' : 'disabled' }}" data-title="Edit" href="{{ URL::to('add_feature/' . $detail->feature_id.(($module_id) ? ('/'.$module_id) : '' )) }}" data-toggle="tooltip" data-placement="top" title="Edit Feature"><span class="glyphicon glyphicon-pencil"></span></a>
                                                    <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(12, 'D') ? '' : 'disabled' }}" data-title="Delete" href="javascript:;" rel="{{ URL::to('delete_feature/' . $detail->feature_id.(($module_id) ? ('/'.$module_id) : '' )) }}" data-toggle="tooltip" data-placement="top" title="Delete Feature" feature-name="{{ $detail->feature }}" id="{{ $detail->feature_id }}"><span class="glyphicon glyphicon-trash"></span></a></td>
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
        var feature_name = $(this).attr("feature-name");
        var id = $(this).attr("id");
        swal({
            title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete Feature <strong>"+id+" - "+feature_name +"</strong><br/> Are you sure?</div>",
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




