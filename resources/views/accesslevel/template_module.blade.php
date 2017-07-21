<div class="panel-group" id="accordion">
@foreach ($modules as $module) 
<?php $mid = $module->module_id; ?>	  	
    <div class="panel panel-default">
        <div class="panel-heading">
        	<div class="form-check form-check col-md-1">
				<label class="form-check-label">
				    <input class="form-check-input checkboxclick" type="checkbox"  value="{{$mid}}" rel="#collapseOne-{{$mid}}" name ="module_id[]" {{ (isset($module_ids) && in_array($mid, $module_ids)) ? "checked" : "" }} >
				</label>
			</div>
	        <h4 class="panel-title">
	            {{ $module->description }}
	        </h4>
        </div>
        <div id="collapseOne-{{$mid}}" class="panel-collapse collapse {{ (isset($module_ids) && in_array($mid, $module_ids)) ? "in" : "" }}">
	        <div class="panel-body">
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
								<input type="checkbox" value="D"name ="access_{{$mid}}_{{$fid}}_d" <?php echo in_array('D', $access) ? "checked" :'' ?> > Delete
								<span></span>
							</label>
							<label class="mt-checkbox">
								<input type="checkbox" value="A"name ="access_{{$mid}}_{{$fid}}_a" <?php echo in_array('A', $access) ? "checked" :'' ?> > Add
								<span></span>
							</label>
							<label class="mt-checkbox">
								<input type="checkbox" value="V"name ="access_{{$mid}}_{{$fid}}_v" <?php echo in_array('V', $access) ? "checked" :'' ?> > View
								<span></span>
							</label>
							<label class="mt-checkbox">
								<input type="checkbox" value="E"name ="access_{{$mid}}_{{$fid}}_e" <?php echo in_array('E', $access) ? "checked" :'' ?> > Edit
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
		
		