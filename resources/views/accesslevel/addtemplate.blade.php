@extends('layouts.app')

@section('content')
<style>
#accordion .panel-heading .form-check{margin-top:-3px}
.mt-checkbox-inline label.mt-checkbox {font-weight: 400;}
.mt-checkbox-inline label{margin: 0px 10px;}
.col-md-4.control-label{ padding-top:2px !important;}
#template-module .panel-collapse .col-md-12 {float: left;margin-bottom: 6px;width: 100%;}
.mt-checkbox > input {float: left;margin-right: 5px;margin-top: 3px;}
#menus ul {list-style-type: none;}
.save_button{margin-right: 15px;}
.back-button {margin-left: 15px;}
input.area_user {margin-right: 6px;}
</style>

<div class="container-fluid"> 
	<input type="hidden" />
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
                    <h3 class="text-center">Manage Templates</h3>
                    <div class="panel panel-default">
						<div class="panel-heading">{{isset($detail_edit_template->template_id) ? "Edit " : "Add " }} Template</div>
                        <div class="panel-body">
                            <form class="form-horizontal form" role="form" method="POST" action="" id ="templateform">
                                {{ csrf_field() }}
                                <input type="hidden" id="unique_temp_id" value="{{ isset($detail_edit_template->template_id) ? $detail_edit_template->template_id : 0 }}" />
                                <div class="form-group{{ $errors->has('temp_name') ? ' has-error' : '' }}">
                                    <label for="temp_nam" class="col-md-3 control-label">Template Name</label>
                                    <div class="col-md-6">
                                        <input id="temp_name" type="text" class="form-control required" name="temp_name"  value="{{isset($detail_edit_template->description) ? $detail_edit_template->description : "" }}" template-id="{{isset($detail_edit_template->template_id)? $detail_edit_template->template_id :0}}" autofocus>
                                        @if ($errors->has('temp_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('temp_name') }}</strong>
                                        </span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <input class="superAdmin area_user" type="checkbox" name="is_super_admin" id="admincheck" value= "<?php if(isset($detail_edit_template->is_super_admin)){echo 1;} ?>" {{ (isset($detail_edit_template-> is_super_admin) && ($detail_edit_template-> is_super_admin == 1)) ? "checked" : "" }}><label class="control-label">Set as Super Admin</label>
                                    </div>
                                </div>
                                <div id="template-module"></div>
                                <!-- menus start -->
                                <div class ="row">
                                    <div class="col-md-12" id="menus">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">Menus</div>
                                            <div class="panel-body appen-sub-0"></div>            
                                        </div>
                                    </div>
                                </div>
                                <!-- menus end -->
                                <div class="form-group row">
                                  <div class="col-md-6">
                                      <a type="button" class="btn btn-default back-button" href="{{ URL('list_template') }}">
                                      Back
                                      </a>
                                  </div>
                                  <div class="col-md-6">
                                      <button type="submit" class="btn btn-primary pull-right save_button">
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
	</div>
</div>

<script>
function get_child_menus(id,menu_ids){
	var _token = $("meta[name='csrf-token']").attr('content');
	$.ajax({
		url: ajax_url+'/get_child_menu',
		data: {_token, id, menu_ids},
		type: "POST",
        async: false,
		success: function(response){
		   $(".appen-sub-"+id).append(response);
		}
	});
}
$(function(){
	var menu_ids = {};
	<?php if(isset($menu_ids) && (isset($detail_edit_template->is_super_admin) && $detail_edit_template->is_super_admin == 0 )){ ?>
	var menu_ids = <?php echo json_encode($menu_ids); ?>;
	<?php } if(isset($detail_edit_template->template_id)){ ?>
            get_template_module("<?php echo $detail_edit_template->template_id; ?>");
    <?php }else{ ?>
			get_template_module(0);
		<?php }
    ?>
	get_child_menus(0, menu_ids);
    <?php if(isset($detail_edit_template->is_super_admin) && $detail_edit_template->is_super_admin == 1 ){?>
        $("#menus input.append-child-menu").prop( "checked", true );
    <?php } ?>
	$.each(menu_ids, function(k,v){
		get_child_menus(v, menu_ids);
	});
	$.each(menu_ids, function(k,v){
		$("#menus input#click-by-"+v).prop( "checked", true );
	});
	$("#menus").on("click", ".append-child-menu", function(){
        var id = $(this).val();
		var menu_ids = {};
		if($(this).is(":checked")){
			get_child_menus(id, menu_ids);
		}else{
			$(".remove-append-"+id).remove();
		}
    });
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
});

$(function () {
	$("#template-module").on("click", ".click_module", function(){
		var _this_rel = $(this).attr("rel");
		if($(_this_rel).hasClass("in")){
			$(_this_rel).collapse("hide");
		}else{
			$(_this_rel).collapse("show");
		}
	});
    $("#corporation_name").change(function () {
        var template_id = $(this).attr('template-id');
        var corp_id = $(this).val();
    });
    $('#admincheck').change(function () {   
        $('input:checkbox').prop('checked', this.checked);      
        if($(this).is(":checked")){
            $(this).val(1);
        }else{
            $(this).val(0);
        }   
    });
});
function get_template_module(template_id){
	var corp_id = 0;
    $.ajax({
        url: ajax_url+'/template_module',
        data: {corp_id, template_id},
        type: "GET",
        success: function(res){
            $('#template-module').html(res);
        }
    });
}
</script>
@endsection

