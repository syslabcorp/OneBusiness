@extends('layouts.app')

@section('content')
<h3 class="text-center">Add Menu</h3>
<div class="container-fluid">
    <div class="row">
		@if(Session::has('alert-class'))
            <div class="alert alert-success"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @elseif(Session::has('flash_message'))
            <div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @endif
		<div class="col-md-2">
			<div id="treeview_json"></div>
		</div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit->id) ? "Edit " : "Add " }} Menu</div>
                <div class="panel-body">
                    <form class="form-horizontal form-menu" role="form" method="POST" action="" id ="menuform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('parent_name') ? ' has-error' : '' }}">
                            
                            @if($parent_id != 0)
                            <label for="parent_menu" class="col-md-4 control-label">Parent Menu</label>
                            <div class="col-md-6">
                                <?php 
                                $title = DB::table('menus')->where('id', $parent_id)->first();
                                ?>
                                <select class="form-control parent" id="parent_menu" name="parent_id">
                                <option  value="{{$title->parent_id}}">{{$title->title}}</option>
                                </select>
                            </div>
                            @else
                            <input type="hidden" name="parent_id" value="0">
                            @endif
                            
                        </div>
                        <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                            <label for="icon" class="col-md-4 control-label">Icon</label>
                            <div class="col-md-6">
                                <input id="icon" type="text" class="form-control" name="icon" value="{{isset($detail_edit->icon) ? $detail_edit->icon : "" }}" autofocus>
                                @if ($errors->has('icon'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('icon') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-4 control-label">Title</label>
                            <div class="col-md-6">
                                <input id="title" type="text" class="form-control required" name="title" value="{{isset($detail_edit->title) ? $detail_edit->title : "" }}" autofocus>
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                            <label for="url" class="col-md-4 control-label">Url</label>
                            <div class="col-md-6">
                                <input id="url" type="text" class="form-control url" name="url" value="{{isset($detail_edit->url) ? $detail_edit->url : "" }}" autofocus>
                                @if ($errors->has('url'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('url') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div id="template-module"></div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{isset($detail_edit->id) ? "Save " : "Create " }}
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
    $("#menuform").validate();      
});
</script>
@endsection

