@extends('layouts.app')

@section('content')
<style>
.activecheck {padding-top: 9px;}
#branch_name {margin-right: 5px;}
.save_button{margin-right: 15px;}
.back-button {margin-left: 15px;}
label.mt-checkbox {font-weight: 400;}
</style>
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
                    <h3 class="text-center">Manage Groups</h3>
                    <div class="panel panel-default">
                        <div class="panel-heading">{{isset($detail_edit->group_ID) ? "Edit " : "Add " }}Group</div>
                        <div class="panel-body">
                            <form class="form-horizontal form-group" role="form" method="POST" action="" id ="groupform">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('group_name') ? ' has-error' : '' }}">
                                    <label for="group_nam" class="col-md-4 control-label">Group Name:</label>
                                    <div class="col-md-6">
                                        <input id="group_name" type="text" class="form-control required" name="group_desc" group-id="" value="{{isset($detail_edit->desc) ? $detail_edit->desc : "" }}" autofocus>
                                        @if ($errors->has('group_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('group_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('active_group') ? ' has-error' : '' }}">
                                    <label for="active_grp" class="col-md-4 control-label">Active:</label>
                                    <div class="col-md-6 activecheck">
                                        <input id="active_group" type="checkbox" name="active_group" {{isset($detail_edit->status) && $detail_edit->status == 1 ? "checked" : "" }} >
                                    </div>
                                </div>
                                <!-- menus start -->
                                <div class ="row">
                                    <div class="col-md-12" id="branch">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Branches</div>
                                            <div class="form-group{{ $errors->has('branch_name') ? ' has-error' : '' }}">
                                            <div class="panel-body">
                                            @foreach ($branches as $corp_name=>$corp)
                                                <div class="col-md-12">
                                                    <h4>{{ $corp_name }}</h4>
                                                    @foreach($corp as $cityname=>$city)
                                                        <div class="col-md-5">
                                                            <label>{{ $cityname }}</label>
                                                            @foreach($city as $branch)
                                                            <div class="col-md-12">
                                                            <label class="mt-checkbox branchcheckbox">
                                                                <input id="branch_name" type="checkbox" name="branch[]" value="{{$branch->Branch }}"
                                                                <?php 
                                                                if(isset($branch_ids)){ echo in_array($branch->Branch, $branch_ids) ? "checked" : '' ;
                                                                }
                                                                ?> >
                                                                {{$branch->ShortName }}
                                                            </label>
                                                                @if ($errors->has('branch_name'))
                                                                    <span class="help-block">
                                                                        <strong>{{ $errors->first('branch_name') }}</strong>
                                                                    </span>
                                                                @endif
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                            </div>
                                        </div>         
                                        </div>
                                    </div>
                                </div>
                                <!-- menus end -->
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <a type="button" class="btn btn-default back-button" href="{{ URL('list_group') }}">
                                        Back
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary pull-right save_button">
                                        {{isset($detail_edit->group_ID) ? "Save " : "Create " }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $("#groupform").validate();   
});
</script>
@endsection

