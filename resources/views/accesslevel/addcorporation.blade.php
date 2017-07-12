@extends('layouts.app')

@section('content')
<h3 class="text-center">Manage Corporations</h3>
<div class="container-fluid">
    <div class="row">
		<div class="col-md-2">
			<div id="treeview_json"></div>
		</div>
        <div class="col-md-8">
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
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
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
<script>
$(function(){
    $("#corporationform").validate();   
});
</script>
@endsection