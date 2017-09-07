@extends('layouts.app')

@section('content')
<style>

#branch_name {
    margin-right: 15px;
}
.user_temp {
    margin-left: 30px;
}
input.area_user {
    margin-right: 6px;
}
.branch_assign{
    padding: 0px !important;
}
.combine_branch {
    padding-left: 27px;
}
.save_button{
    margin-right: 15px;
}
</style>
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
                    <h3 class="text-center">Manage Users</h3>
                    <div class="panel panel-default">
                        <?php
                            if(!empty($detail_edit_sysuser->uname)){
                                $username = $detail_edit_sysuser->uname;
                            }else{
                                $username = $detail_edit_sysuser->UserName;
                            } 
                        ?>
                        <div class="panel-heading">USER ACCESS PROFILE&nbsp;:&nbsp;{{ $username }}</div>
                        <div class="panel-body">
                            <form class="form-horizontal form-group" role="form" method="POST" id ="userform">
                                {{ csrf_field() }}
                                <input type="hidden" name="userid" id="userid" value="{{isset($detail_edit_sysuser->UserID) ? $detail_edit_sysuser->UserID : '' }}">

                                <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                                    <label for="user_nam" class="col-md-12">ACCESS RIGHTS TEMPLATE</label>
                                    <div class="col-md-6 user_temp">
                                        <select class="form-control required" id="temp_name" name="temp_id">
                                            <option value="">Select a Template</option>
                                                @foreach ($template as $temp) 
                                                    <option value="{{ $temp ->template_id }}" {{ (isset($detail_edit_sysuser->rights_template_id) && ($detail_edit_sysuser->rights_template_id == $temp ->template_id)) ? "selected" : "" }} >{{ $temp->description }}</option> 
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('active_group') ? ' has-error' : '' }}">
                                    <label for="active_grp" class="col-md-12">BRANCH ASSIGNMENT</label>
                                    <div class="col-md-12 user_temp">
                                    <div class="branch_assign col-md-1">Area Type:</div>
                                        <div class="col-md-2">
                                            <label class="mt-radio">
                                                <input class="area_type area_user" id="areatype" type="radio" name="area_type" value="PR" {{ (isset($detail_edit_sysuser->Area_type) && ($detail_edit_sysuser->Area_type == "PR")) ? "checked" : ''  }}
                                                >Province
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="mt-radio">
                                                <input class="area_type area_user" id="areatype" type="radio" name="area_type" value="CT" {{ (isset($detail_edit_sysuser->Area_type) && ($detail_edit_sysuser->Area_type == "CT")) ? "checked" : ''  }} >
                                                City
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="mt-radio">
                                                <input class="area_type area_user" id="areatype" type="radio" name="area_type" value="BR" {{ (isset($detail_edit_sysuser->Area_type) && ($detail_edit_sysuser->Area_type == "BR")) ? "checked" : ''  }}>
                                                Branch
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class ="row" class="branch_assignment" id="branch_assignment">
                                    
                                </div>
                                <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                                    <label for="user_nam" class="col-md-12">REMITTANCE GROUP</label>
                                    <div class="col-md-12 user_temp">
                                        @foreach($group as $groups)
                                        <div class="col-md-2 branch_assign">
                                            <input id="group_name" type="checkbox" name="group[]" value="{{$groups->group_ID }}" class="area_user"
                                            <?php 
                                            if(isset($group_ids)){ echo in_array($groups->group_ID, $group_ids) ? "checked" : '' ;
                                            }
                                            ?> >
                                            {{$groups->desc }}

                                            @if ($errors->has('group_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('group_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        @endforeach

                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <a type="button" class="btn btn-default" href="{{ URL('list_user') }}"><span class="glyphicon glyphicon-arrow-left"></span>
                                        Back
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary pull-right save_button">
                                        {{isset($detail_edit_sysuser->UserID) ? "Save " : "Create " }}
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
    $("#userform").validate();  

});
function get_area_type(value,userid){
    var _token = $("meta[name='csrf-token']").attr("content");
    var value1 = value;
    if (value == "CT") {
        var activevalue = "city";
    }else if(value == "BR"){
        var activevalue = "branch";
    }else{
        var activevalue = "provinces";
    }
    $.ajax({
        url: ajax_url+'/'+ activevalue +'/'+ userid ,
        type: "POST",
        data: {_token},
        success: function(response){
          $('#branch_assignment').html(response);
        }
    });
}
$(document).on("click", ".area_type", function(){
    var userid = $("#userid").val();
    var value = $(this).val();
    get_area_type(value,userid);
});
$( window ).load(function() {
    var value = $('.area_type:checked').val();
    var userid = $("#userid").val();
    if(typeof(value) === 'undefined'){
        value = 'PR';
        $(":radio[value=PR]").attr("checked","true");
        get_area_type(value,userid);
    }else{
        get_area_type(value,userid);
    }
});
</script>

@endsection

