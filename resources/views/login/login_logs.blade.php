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
            <div class="alert alert-success alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @elseif(Session::has('flash_message'))
            <div class="alert alert-danger alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @endif
        <div class="col-md-12">
        <h3 class="text-center">Active Users</h3>
            <div class="panel panel-default">
                <div class="panel-heading">Active Users</div>
                <div class="panel-body table-responsive">
                   <table id="list_log" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SNo.</th>
                                <th>Username</th>
                                <th>Login Type</th>
                            </tr>
                        </thead>
                        <tbody>

                                @foreach($logs_data as $key=>$data)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $data->user_name }}</td>
                                    <td>{{ $logtype[$data->login_type] }}</td>
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
<script>
$(document).ready(function() {
    $('#list_log').DataTable();
});
</script>
@endsection


