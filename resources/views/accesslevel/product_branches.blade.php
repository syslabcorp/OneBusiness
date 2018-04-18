<div class="panel panel-default">
	<div class="panel-heading">
		<label class="control-label mt-checkbox">
		<input class="branch-all" type="checkbox" name="branchall" id="branchall"> Branch
		</label>
	</div>
	<div class="panel-body puchase-panel">
	    <table id="pro_line" class="table table-striped table-bordered" cellspacing="0" width="100%">
		    <tbody>
		    	@if($branches->isEmpty())
		    	<tr><td>No branches or branches are not allowed for your account </td></tr> 
		    	@else
		    		@foreach ($branches as $branch) 
			        <tr>
			            <td><input class="branchselect" type="checkbox" name="branch[]" id="branchselect" value="{{ $branch->Branch }}" onchange="enablecheckbox()" {{ (isset($probranch_ids) && in_array($branch->Branch, $probranch_ids)) ? "checked" : "" }} ></td>
			            <td>{{ $branch->ShortName }}</td>
			        </tr> 
			        @endforeach  
		        @endif

		    </tbody>
	    </table>
    </div>
</div>
<script>
$("#branchall").change(function(){
    if(this.checked){ 
        $(".branchselect").each(function(){
            this.checked=true;
        });         
    }else{
        $(".branchselect").each(function(){
            this.checked=false;
        });              
    }
    enablecheckbox();   
});
$(function(){
    var isSelected = [];
	$('.branchselect').each(function() {
	    if ($(this).is(":checked")) {
	        isSelected.push("true");
	    } else {
	        isSelected.push("false");  
	    }
	});
	if ($.inArray("false", isSelected) < 0) {
		if(isSelected.length != 0){
    		$(".branch-all").attr("checked", true);
    	} 
	}
});

</script>