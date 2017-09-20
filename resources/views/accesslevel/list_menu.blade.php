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
			 <h3 class="text-center">Manage Menus</h3>
    <div class="row">    
            <div class="panel panel-default">
                <div class="panel-heading"><?php echo ($parentcrumb == "0") ? "List of Menus" : $parentcrumb; ?><a href="{{ URL('add_menu/'.$parent_id) }}" class="pull-right">Add Menu</a></div>
                <div class="panel-body">
                <div class="table-responsive">
                   <table id="list_menu" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SNo.</th>
                                <th>Title</th>
                                <th>Icon</th>
                                <th>Url</th>
                                <th class="text-center">Sub Menu</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detail as $key=>$det)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $det->title }}</td>
									<td class="text-center"><?php echo "<span class='$det->icon'></span>"; ?></td>
                                    <td>{{ $det->url }}</td>
                                    <td class="text-center"><a href="{{ URL::to('list_menu/'.$det->id) }}">{{ isset($submenu_count[$det->id]) ? count($submenu_count[$det->id]) : 0 }}</a></td>
                                    <td><a class="btn btn-primary btn-md blue-tooltip" data-title="Edit" href="{{ URL::to('add_menu/' .$det->parent_id.'/'.$det->id) }}" data-toggle="tooltip" data-placement="top" title="Edit Menu"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a class="btn btn-danger btn-md sweet-4 red-tooltip" data-title="Delete" href="#" rel="{{ URL::to('delete_menu/' . $det->id) }}" data-toggle="tooltip" data-placement="top" title="Delete Corporation" menu-name="{{ $det->title }}" id="{{ $det->id }}" has-sub-menus ="{{ isset($submenu_count[$det->id]) ? ' and all its sub menus.' : '' }}"><span class="glyphicon glyphicon-trash"></span></a></td>
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
</div>
<script>

$(document).ready(function() {
    $('#list_menu').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        var menu_name = $(this).attr("menu-name");
        var has_sub_menus = $(this).attr("has-sub-menus");
        var id = $(this).attr("id");
        swal({
            title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete Menu <strong>"+id+" - "+menu_name +"  " + has_sub_menus +"</strong><br/> Are you sure?</div>",
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


