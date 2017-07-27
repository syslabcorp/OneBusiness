@extends('layouts.app')

@section('content')
<style>
#accordion .panel-heading .form-check{margin-top:-3px}
.mt-checkbox-inline label.mt-checkbox {font-weight: 400;}
.mt-checkbox-inline label{margin: 0px 10px;}
.col-md-4.control-label{ padding-top:2px !important;}
#template-module .panel-collapse .col-md-12 {float: left;margin-bottom: 6px;width: 100%;}
.mt-checkbox > input {float: left;margin-right: 5px;margin-top: 3px;}
</style>
<h3 class="text-center">Manage Templates</h3>
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
                <div class="panel-heading">{{isset($detail_edit_template->template_id) ? "Edit " : "Add " }} Template</div>
                <div class="panel-body">
                    <form class="form-horizontal form" role="form" method="POST" action="" id ="templateform">
                        {{ csrf_field() }}
						<input type="hidden" id="unique_temp_id" value="{{ isset($detail_edit_template->template_id) ? $detail_edit_template->template_id : 0 }}" />
                        <div class="form-group{{ $errors->has('temp_name') ? ' has-error' : '' }}">
                            <label for="temp_nam" class="col-md-4 control-label">Template Name</label>
                            <div class="col-md-6">
                                <input id="temp_name" type="text" class="form-control required" name="temp_name"  value="{{isset($detail_edit_template->description) ? $detail_edit_template->description : "" }}" template-id="{{isset($detail_edit_template->template_id)? $detail_edit_template->template_id :0}}" autofocus>
                                @if ($errors->has('temp_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('temp_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                     
                        <div id="template-module"></div>
                        <!-- menus start -->
                        <div class ="row">
                            <div class="col-md-12" id="menus">
                            <div class="panel panel-default">
                                <div class="panel-heading">Menus</div>
                                <div class="panel-body">   
                                @foreach ($menus as $menu) 
                                    <div class="col-md-4">
                                        <div class="mt-checkbox-inline">
                                            <label class="mt-checkbox">
                                                <input type="checkbox" value="{{$menu->id}}"name ="menu[]"
                                                {{ (isset($menu_ids) && in_array($menu->id, $menu_ids)) ? "checked" : "" }} 
                                                > {{$menu->title}}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                                </div>            
                            </div>
                            </div>
                        </div>
                            <!-- menus end -->
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{isset($detail_edit_template->template_id) ? "Save " : "Create " }}
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
   
    $("#templateform").validate({
		rules: {
			temp_name: {
				required: true,
				remote: {
					url: ajax_url+"/template_exist",
					type: "GET",
					data: {
						temp_name: function() {
							return $( "#temp_name" ).val();
						},unique_temp_id: function() {
							return $( "#unique_temp_id" ).val();
						}
					}
				}
			}
		},
		messages:{
			temp_name:{
				remote: "Template name already exist."
			}
		}
	});   
    $("#template-module").on('click','.checkboxclick',function(){
    
        var objectID=$(this).attr('rel');

        if($(objectID).hasClass('in'))
        {
            $(objectID).collapse('hide');
        }
        
        else{
            $(objectID).collapse('show');
        }
    });    
});
$(window).load(function() {
     var template_id = $("#temp_name").attr('template-id');
     get_template_module(template_id);
});
 
function get_template_module(template_id){
    $.ajax({
        url: ajax_url+'/template_module',
        data: {template_id},
        type: "GET",
        success: function(res){
            $('#template-module').html(res);
        }
    });
}
</script>
@endsection

