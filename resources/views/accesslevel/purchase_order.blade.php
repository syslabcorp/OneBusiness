@extends('layouts.app')

@section('content')
<style>
.pogroup {padding-right: 5px; padding-left: 5px;}
.puchase-panel {padding: 5px !important;}
.panel-body.puchase-panel.retail-items {height: 492px;overflow-y:auto;}
.panel-body.puchase-panel {height: 440px;overflow-y: auto;}
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
                    <h3 class="text-center">Manage Purchase Order Templates</h3>
                    <div class="panel panel-default">
						<div class="panel-heading">{{isset($detail_edit_template->template_id) ? "Edit " : "New " }} PO Template: <b>{{ $cities->City }}</b></div>
                        <div class="panel-body">
                            <form class="form-horizontal form" role="form" method="POST" action="" id ="potemplateform">
                                {{ csrf_field() }}
                                <input type="hidden" name="proid" id="proid" value="{{isset($detail_edit_temp_hdr->po_tmpl8_id) ? $detail_edit_temp_hdr->po_tmpl8_id : '' }}">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="temp_nam" class="col-md-4 control-label">Template Name</label>
                                        <div class="col-md-8">
                                            <input id="temp_name" type="text" class="form-control required" maxlength=30 name="po_tmpl8_desc"  value="{{isset($detail_edit_temp_hdr->po_tmpl8_desc) ? $detail_edit_temp_hdr->po_tmpl8_desc : old('po_tmpl8_desc') }}"autofocus>
                                            @if ($errors->has('temp_name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('temp_name') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    
									<div class="col-md-1">
									<label class="mt-checkbox">
										<input type="checkbox" name="active" id="active" <?php if(isset($detail_edit_temp_hdr->active) && $detail_edit_temp_hdr->active == 1){ echo 'checked'; }else{echo '';}?>> Active
										<span></span>
									</label>
									</div>
									<div class="col-md-4">
										<label for="temp_nam" class="col-md-6 control-label">Ave. Qty Cycle</label>
										<div class="col-md-6">
										<div class="input-group" id="requestorPhoneLast">
											<input id="area_cycle" type="text" class="form-control required number" name="po_avg_cycle"  value="{{isset($detail_edit_temp_hdr->po_avg_cycle) ? $detail_edit_temp_hdr->po_avg_cycle : 30 }}" autofocus>
											<span class="input-group-addon">
												Days
											</span>
										</div>
										</div>
									</div>
									</div>
                                <div class="row">
                                    <!-- start product branch -->
                                    <div class="col-md-3 pogroup product-branch"><?php echo $branchList; ?></div>
                                    <!-- end product branch -->
                                    <!-- start product line -->
                                    <div class="col-md-3 pogroup">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <label class="control-label mt-checkbox">
                                                    <input class="purchase-all" type="checkbox" id="purchaseall"> Product Line
                                                </label>
                                            </div>
                                            <div class="panel-body puchase-panel">
                                            <table id="pro_line" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                            <tbody>
                                                @foreach ($product_line as $pro_line) 
                                                <tr>
                                                    <td><input class="product_active select" type="checkbox" name="product_active[]" id="product_active" value="{{ $pro_line->ProdLine_ID }}" onclick="GetSelectedproduct()" {{ (isset($proline_ids) && in_array($pro_line->ProdLine_ID, $proline_ids)) ? "checked" : "" }}></td>
                                                    <td>{{ $pro_line->Product }}</td>
                                                </tr> 
                                                @endforeach  

                                            </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end product line -->
                                    <div class="col-md-6 pogroup">
                                        <div class="panel panel-default">
                                            <div class="panel-body puchase-panel retail-items">Please select a product line first.</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                  <div class="col-md-6">
                                      <a type="button" class="btn btn-default back-button" href="{{ URL('list_purchase_order/'.(isset($corp_id) ? $corp_id : 0)) }}">
                                      Back
                                      </a>
                                  </div>
                                  <div class="col-md-6">
                                      <button type="submit" {{ ($is_branch_exist) ? '' : 'disabled' }} class="btn btn-primary pull-right save_button">
                                        {{isset($detail_edit_temp_hdr->po_tmpl8_id) ? "Save " : "Create " }}
                                        </button>
                                  </div>
                                </div>
                                <div id="hiddenmodule-ids" class="hiddenmodule-ids"></div>
                                <div id="hiddenfeature-dave" class="hiddenfeature-dave"></div>
                                
                            </form>
                        </div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="{{ URL('/js/product-order.js') }}"></script>
<script>
$(function(){
    <?php if(isset($proline_ids)){ ?>
        GetSelectedproduct();
    <?php } ?>  
});
</script>
@endsection

