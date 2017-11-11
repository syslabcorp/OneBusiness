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
                <h3 class="text-center">Manage Users</h3>
                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">List of users</div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table id="list_user" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>User ID .</th>
                                            <th>Name</th>
                                            <th>Template Name</th>
                                            <th>Area Type</th>
                                            <th>Remittance Group</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user_detail as $key=>$det)
                                        
                                        <?php 
                                        $grpname = array();
										if(!empty($det->group_ID)){
											$ids = explode(",", $det->group_ID);
											foreach ($ids as $value) {
												if(!empty($grp_IDs[$value])){
													array_push($grpname, $grp_IDs[$value]);
												}
											}
										}
                                        if(isset($det->Area_type) && $det->Area_type == "PR"){ $ar_type = "Province";}
                                        else if(isset($det->Area_type) && $det->Area_type == "CT"){ $ar_type = "City";}
                                        else if(isset($det->Area_type) && $det->Area_type == "BR"){ $ar_type = "Branch";}
                                        else{ $ar_type = "";}
                                        /* username Check*/
                                        if(!empty($det->uname)){
                                            $username = $det->uname;
                                        }else{
                                            $username = $det->UserName;
                                        }
                                        if($det->rights_template_id !== 0){
                                            $template_name = isset($temp_ids[$det->rights_template_id]) ? $temp_ids[$det->rights_template_id] : '';
                                        }else{
                                            $template_name = "";
                                        }
                                        ?>
                                            <tr>
                                                <td>{{ $det->UserID }}</td>
                                                <td>{{ $username }}</td>
                                                <td>{{ $template_name }}</td>
                                                <td>{{ $ar_type }}</td>
                                                <td>{{ implode(", ", $grpname) }}</td>
                                                <td class="text-center"><a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(14, 'E') ? '' : 'disabled' }}" href="{{ URL::to('add_user/' . $det->UserID) }}" data-toggle="tooltip" data-placement="top" title="Edit User"><span class="glyphicon glyphicon-pencil"></span></a>
                                                <!-- <a class="btn btn-danger btn-md sweet-4 red-tooltip {{ \Auth::user()->checkAccessById(14, 'D') ? '' : 'disabled' }}" href="#" rel="{{ URL::to('delete_user/' . $det->UserID) }}" data-id ="{{ $det->UserID }}" data-toggle="tooltip" data-placement="top" title="Delete User"><span class="glyphicon glyphicon-trash"></span></a> --></td>
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
<?php $userId = Auth::id(); print_r($userId);?>
<input type="hidden" id="user_id" name="user_id" value="{{ $userId }}">
<script>
$(document).ready(function() {
     $('#list_user').DataTable({
        "fnDrawCallback": function( oSettings ) {
           $('[data-toggle="tooltip"]').tooltip();    
        },
    });
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        var delete_id = $(this).attr("data-id");
        var userid = $('#user_id').val();
        if(delete_id == userid){
            swal({
            title: "",
            text:  "You cannot delete Logged In User.",
            type:  "warning",
            showCancelButton: false,
            cancelButtonText: "Ok",
            closeOnCancel: true
            });
        }
        else{
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
        }
  
    });
    $('[data-toggle="tooltip"]').tooltip();
  
});
</script>
@endsection


