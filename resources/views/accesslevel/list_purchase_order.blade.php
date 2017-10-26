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
                    <h3 class="text-center">Manage Purchase Order Templates</h3>
                    <div class="row">  
                        <div class="panel panel-default">
                            <div class="panel-heading">Auto Ordering Template<a href="{{ URL('purchase_order/'.(isset($cities[0]->City_ID) ? $cities[0]->City_ID : 0)) }}" class="pull-right update-add-url">Add Template</a></div>
                            <div class="panel-body">
                                <div class="form-group row">
                                    <div class="col-md-5">
                                    <div class="row">
                                        <label for="city_nam" class="col-md-2 control-label">City:</label>
                                        <div class="col-md-9">
                                            <select class="form-control required listcity" id="city" name="city_id">
                                            @foreach ($cities as $city) 
                                                <option {{ ($city ->City_ID == $city_id) ? "selected" : "" }} value="{{ $city ->City_ID }}" >{{ $city->City }} </option> 
                                                @endforeach    
                                        </select>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control required activelist" id="active" name="active">
                                            <option value="1" selected>Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    
                                    </div>
                                </div>
                                <div class="table-responsive row">
                                    <table id="list_templat" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Template Code</th>
                                                <th>Ave Cycle</th>
                                                <th>Active</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list-purchase-orders">
                                            
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
<script src="{{ URL('/js/list-product-order.js') }}"></script>
@endsection