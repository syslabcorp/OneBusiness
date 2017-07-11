@extends('layouts.app')

@section('content')
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
                                <input id="temp_name" type="text" class="form-control required" name="temp_name"  value="{{isset($detail_edit_template->description) ? $detail_edit_template->description : "" }}" autofocus>
                                @if ($errors->has('temp_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('temp_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('corporation_name') ? ' has-error' : '' }}">
                            <label for="corp_nam" class="col-md-4 control-label">Corporation</label>
                            <div class="col-md-6">
                                <select class="form-control required corporation_na" id="corporation_name" name="corporation_id">
                                    <option value="">Choose Corporation Name</option>
                                        @foreach ($corporation as $corp) 
                                            <option {{ (isset($detail_edit_template->corp_id) && ($detail_edit_template->corp_id == $corp ->corp_id)) ? "selected" : "" }} value="{{ $corp ->corp_id }}">{{ $corp->corp_name }}</option>
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div id="template-module"></div>
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
    <?php 
        if(isset($detail_edit_template->corp_id)){ ?>
            get_template_module("<?php echo $detail_edit_template->corp_id; ?>", "<?php echo $detail_edit_template->template_id; ?>");
        <?php }
    ?>
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

$(function () {
    $("#corporation_name").change(function () {
        var corp_id = $(this).val();
        get_template_module(corp_id,0);
    });
});
function get_template_module(corp_id,template_id){
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

