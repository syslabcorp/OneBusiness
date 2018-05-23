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
        <div class="col-md-12">
        <h3 class="text-center">Manage Corporations</h3>
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit->corp_id) ? "Edit " : "Add " }}Corporation</div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="" id ="corporationform">
                        {{ csrf_field() }}
                        <input type="hidden" name="old_db_name"  value="{{isset($detail_edit->database_name) ? $detail_edit->database_name : "" }}">
                        
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
                        <div class="form-group{{ $errors->has('db_name') ? ' has-error' : '' }}">
                            <label for="Database" class="col-md-4 control-label">Database Name</label>
                            <div class="col-md-6">
                                <input id="database_name" type="text" class="form-control required" name="database_name"  value="{{isset($detail_edit->database_name) ? $detail_edit->database_name : "" }}"autofocus>
                                @if ($errors->has('database_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('database_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('corp_name') ? ' has-error' : '' }}">
                            <label for="corp_nam" class="col-md-4 control-label">Business Type</label>
                            <div class="col-md-6">
                                <select class="form-control required" id="corp_type" name="corp_type">
                                    <option value="">Choose Corporation Type</option>
                                        @foreach ($corporation_type as $corp_type) 
                                            @if($corp_type->corp_type != "")
                                            <option {{ (isset($detail_edit->corp_type) && ($detail_edit->corp_type == $corp_type ->corp_type)) ? "selected" : "" }} value="{{ $corp_type ->corp_type }}">{{ $corp_type->corp_type }}</option>
                                            @endif
                                        @endforeach
                                </select>
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