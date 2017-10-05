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
            <h3 class="text-center">Manage Features</h3>
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit_feature->feature_id) ? "Edit " : "Add " }}Feature</div>
                <div class="panel-body">
                    <form class="form-horizontal form" role="form" method="POST" action="" id ="featureform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                            <label for="module_nam" class="col-md-4 control-label">Module Description</label>
                            <div class="col-md-6"> 
                                <select class="form-control required" id="module_name" name="module_id">
                                    <option value="">Choose Module</option>
                                        @foreach ($module as $modul) 
                                            <option {{ (isset($detail_edit_feature->module_id) && ($detail_edit_feature->module_id == $modul ->module_id)) ? "selected" : "" }} value="{{ $modul ->module_id }}">{{ $modul->description }}</option>
                                          
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('feature_name') ? ' has-error' : '' }}">
                            <label for="sysfeature" class="col-md-4 control-label"></label>
                            <div class="col-md-6">
                                <label class="mt-checkbox">
                                    <input id="system_use" type="checkbox" name="system_use"  autofocus {{ (isset($detail_edit_feature->module_id) && ($detail_edit_feature->module_id == '0' )) ? "checked" : "" }} > For System use
                                </label>
                                
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('feature_name') ? ' has-error' : '' }}">
                            <label for="feature" class="col-md-4 control-label">Feature</label>
                            <div class="col-md-6">
                                <input id="feature_name" type="text" class="form-control required" name="feature_name"  value="{{isset($detail_edit_feature->feature) ? $detail_edit_feature->feature : "" }}" autofocus>
                                @if ($errors->has('feature_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('feature_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <a type="button" class="btn btn-default" href="{{ URL('list_feature') }}">
                                Back
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary pull-right save_button">
                                    {{isset($detail_edit_feature->feature_id) ? "Save " : "Create " }}
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
    $("#featureform").validate(); 
    <?php if(isset($detail_edit_feature->module_id) && ($detail_edit_feature->module_id == '0' )){ ?>
        $("#module_name").attr("disabled", true);
    <?php } ?>
     $("#system_use").change(function(){
        if(this.checked){ 
            $("#module_name").attr("disabled", true);
                         
        }else{
            $("#module_name").attr("disabled", false);
        }
    });  
});

</script>
@endsection

