@extends('layouts.custom')

@section('content')

<h3 class="text-center">Manage Locations</h3>
<div class="container-fluid">
    <div class="row">
        @if(Session::has('alert-class'))
            <div class="alert alert-success"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @elseif(Session::has('flash_message'))
            <div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @endif
	     <div class="col-md-12">
		    <div class="panel panel-default">
		    	<div class="panel-heading">{{isset($detail_edit->Prov_ID)? "Edit Province: " : "Add Province" }} {{isset($detail_edit->Prov_ID)? $detail_edit->Province : "" }} </div>
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
						<div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="{{ url('/list_provinces') }}" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</a>
                                            </div>
                                            <div class="col-sm-6">
                                                {!! csrf_field() !!}
                                                <button type="submit" class="btn btn-success pull-right">   {{isset($detail_edit->Prov_ID) ? "Save " : "Create " }}</button>
                                            </div>
                                        </div>
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