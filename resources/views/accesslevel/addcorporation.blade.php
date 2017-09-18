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
        <div class="col-md-12">
        <h3 class="text-center">Manage Corporations</h3>
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit->corp_id) ? "Edit " : "Add " }}Corporation</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="" id ="corporationform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('corp_name') ? ' has-error' : '' }}">
                            <label for="Corporation" class="col-md-4 control-label">Corporation</label>
                            <div class="col-md-6">
                                <input id="corporation_title" type="text" class="form-control required" name="corporation_title"  value="{{isset($detail_edit->corp_name) ? $detail_edit->corp_name : "" }}"autofocus>
                                @if ($errors->has('corporation_title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('corporation_title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-6">
                              <a type="button" class="btn btn-default" href="{{ URL('list_corporation') }}">
                              Back
                              </a>
                          </div>
                          <div class="col-md-6">
                              <button type="submit" class="btn btn-primary pull-right save_button">
                                    {{isset($detail_edit->corp_id) ? "Save " : "Create " }}
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
    $("#corporationform").validate();   
});
</script>
@endsection