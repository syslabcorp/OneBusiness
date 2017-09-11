@extends('layouts.app')
@section('content')

<h3 class="text-center">Manage Locations</h3>
<div class="container-fluid">
    <div class="row">
        @if(Session::has('alert-class'))
            <div class="alert alert-success"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @elseif(Session::has('flash_message'))
            <div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @endif
	    <div class="col-md-2">
	<!--menu here-->
	    </div>
	    <div class="col-md-8">
		    <div class="panel panel-default">
		    	<div class="panel-heading">{{isset($detail_edit->Prov_ID)? "Edit " : "Add " }} Province</div>
		    	<div class="panel-body">

                        <form class="form-horizontal" role="form" method="POST" action="" id ="Provinceform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('parent_prov') ? ' has-error' : '' }}">
                            <label for="Province" class="col-md-4 control-label">Province</label>
                            <div class="col-md-6">
                                <input id="Province_name" type="text" class="form-control required" name="Province_name"  value="{{isset($detail_edit->Province) ? $detail_edit->Province : "" }}" autofocus>
                                @if ($errors->has('Province_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('Province_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{isset($detail_edit->Prov_ID) ? "Save " : "Create " }}
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
    $("#Provinceform").validate();   
});
</script>
@endsection