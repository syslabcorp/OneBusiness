@extends('layouts.app')

@section('content')
<h3 class="text-center">Manage Modules</h3>
<div class="container-fluid">
    <div class="row">
		<div class="col-md-2">
			<div id="treeview_json"></div>
		</div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit_module->module_id) ? "Edit " : "Add " }} Module</div>
                <div class="panel-body">
                    <form class="form-horizontal form" role="form" method="POST" action="" id ="moduleform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('corp_name') ? ' has-error' : '' }}">
                            <label for="corp_nam" class="col-md-4 control-label">Corporation Name</label>
                            <div class="col-md-6">
                                <select class="form-control required" id="corp_name" name="corp_id">
                                    <option value="">Choose Corporation Name</option>
                                        @foreach ($corporation as $corp) 
                                            <option {{ (isset($detail_edit_module->corp_id) && ($detail_edit_module->corp_id == $corp ->corp_id)) ? "selected" : "" }} value="{{ $corp ->corp_id }}">{{ $corp->corp_name }}</option>
                                          
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                            <label for="module" class="col-md-4 control-label">Module</label>
                            <div class="col-md-6">
                                <input id="module_name" type="text" class="form-control required" name="module_name"  value="{{isset($detail_edit_module->description) ? $detail_edit_module->description : "" }}" autofocus>

                                @if ($errors->has('module_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('module_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{isset($detail_edit_module->module_id) ? "Save " : "Create " }} 
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $("#moduleform").validate();   
});
</script>
@endsection
