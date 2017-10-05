@if(isset($sys_features) && (!empty($sys_features)))
	<div class="col-md-12">
	<h4>SYSTEM</h4>
	<div class="panel-group">
		<?php $mid = 0; ?>  	
		<div class="panel panel-default">
			<!--div class="panel-heading">
				<div class="form-check form-check col-md-1">
					<label class="form-check-label">
						<input class="form-check-input checkboxclick" type="checkbox"  value="{{$mid}}" rel="#collapseOne-{{$mid}}" name ="module_id[]" {{ (isset($module_ids) && in_array($mid, $module_ids)) ? "checked" : "" }} >
					</label>
				</div>
				<h4 class="panel-title click_module" rel="#collapseOne-{{$mid}}"> 
				  <span>  System Configuration</span>
				</h4>
			</div-->
			<div class="">
				<div class="panel-body">
					@foreach ($sys_features as $feature)
					<?php $fid= $feature->feature_id;  
					
					if(isset($fet_access[$fid])){
						$access =array();
						if($fet_access[$fid] != '0'){
							$type = $fet_access[$fid];
							$mystr = array($type);
							$strlen = strlen($fet_access[$fid]);
							for($i=0; $i<$strlen; $i++){   
								array_push($access, $type[$i]); 
							}	
						}	
					}else{
						$access =array();
					}
					 ?>
						<input type="hidden" name="feature_{{$mid}}[]" value="{{$fid}}">
						<div class="col-md-12">
						<label for="faeture_nam" class="col-md-4 control-label">{{ $feature->feature }}</label>
						<div class="col-md-8">
							<div class="mt-checkbox-inline">
								<label class="mt-checkbox">
									<input type="checkbox" value="D" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_d" <?php echo in_array('D', $access) ? "checked" :'' ?> > Delete
									<span></span>
								</label>
								<label class="mt-checkbox">
									<input type="checkbox" value="A" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_a" <?php echo in_array('A', $access) ? "checked" :'' ?> > Add
									<span></span>
								</label>
								<label class="mt-checkbox">
									<input type="checkbox" value="V" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_v" <?php echo in_array('V', $access) ? "checked" :'' ?> > View
									<span></span>
								</label>
								<label class="mt-checkbox">
									<input type="checkbox" value="E" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_e" <?php echo in_array('E', $access) ? "checked" :'' ?> > Edit
									<span></span>
								</label>
							</div>
						</div>
					</div>
					@endforeach	
				</div>
			</div>
		</div>	
	</div>
</div>
@endif
@foreach ($modules as $corp_name=>$corp) 
<div class="col-md-12">
	<h4>{{ $corp_name }}</h4>
	<div class="panel-group">
	@foreach ($corp as $module) 
	<?php $mid = $module->module_id; ?>	  	
		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="form-check form-check col-md-1">
					<label class="form-check-label">
						<input class="form-check-input checkboxclick" type="checkbox"  value="{{$mid}}" rel="#collapseOne-{{$mid}}" name ="module_id[]" {{ (isset($module_ids) && in_array($mid, $module_ids)) ? "checked" : "" }} >
					</label>
				</div>
				<h4 class="panel-title click_module" rel="#collapseOne-{{$mid}}"> 
				  <span>  {{ $module->description }} </span>
				</h4>
			</div>
			<div id="collapseOne-{{$mid}}" class="panel-collapse collapse {{ (isset($module_ids) && in_array($mid, $module_ids)) ? "" : "" }}">
				<div class="panel-body">
					<?php 
					if ($features[$mid]->isEmpty()) { ?>
					<div class="col-md-12">
						<div  class="col-md-4 nofeature">No features to display</div>
					</div>
					<?php }?>					

					@foreach ($features[$mid] as $feature)
					<?php $fid= $feature->feature_id;  
					
					if(isset($fet_access[$fid])){
						$access =array();
						if($fet_access[$fid] != '0'){
							$type = $fet_access[$fid];
							$mystr = array($type);
							$strlen = strlen($fet_access[$fid]);
							for($i=0; $i<$strlen; $i++){   
								array_push($access, $type[$i]); 
							}	
						}	
					}else{
						$access =array();
					}
					 ?>
						<input type="hidden" name="feature_{{$mid}}[]" value="{{$fid}}">
						<div class="col-md-12">
						<label for="faeture_nam" class="col-md-4 control-label">{{ $feature->feature }}</label>
						<div class="col-md-8">
							<div class="mt-checkbox-inline">
								<label class="mt-checkbox">
									<input type="checkbox" value="D" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_d" <?php echo in_array('D', $access) ? "checked" :'' ?> > Delete
									<span></span>
								</label>
								<label class="mt-checkbox">
									<input type="checkbox" value="A" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_a" <?php echo in_array('A', $access) ? "checked" :'' ?> > Add
									<span></span>
								</label>
								<label class="mt-checkbox">
									<input type="checkbox" value="V" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_v" <?php echo in_array('V', $access) ? "checked" :'' ?> > View
									<span></span>
								</label>
								<label class="mt-checkbox">
									<input type="checkbox" value="E" class="feature_dave" name ="access_{{$mid}}_{{$fid}}_e" <?php echo in_array('E', $access) ? "checked" :'' ?> > Edit
									<span></span>
								</label>
							</div>
						</div>
					</div>
					@endforeach	
				</div>
			</div>
		</div>	
	@endforeach		
	</div>
</div>
@endforeach		
		
		