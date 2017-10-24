<table id="pro_line" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
	        <td><input class="retail-all" type="checkbox" name="retailall" id="retailall"></td>
	        <td>Item Code</td>
	        <td>Brand</td>
	        <td>Pkg</td>
	        <td>Thresh</td>
	        <td>Mult</td>
	    </tr> 
	</thead>
	<tbody>
		@if($s_invtry_hdr->isEmpty())
		<tr><td colspan="6">No Retail Item Exists !!</td></tr> 
		@else
			@foreach ($s_invtry_hdr as $s_invtry) 
	        <tr>
	            <td><input class="retail" type="checkbox" name="item_id[]" id="item_id" value="{{ $s_invtry->item_id }}" {{ (isset($proitems_ids) && in_array($s_invtry->item_id, $proitems_ids)) ? "checked" : "" }}></td>
	            <td>{{ $s_invtry->ItemCode }}</td>
	            <td>{{ isset($brandname[$s_invtry->Brand_ID]) ? $brandname[$s_invtry->Brand_ID] : ''}}</td>
	            <td>{{ $s_invtry->Packaging }}</td>
	            <td>{{ $s_invtry->Threshold }}</td>
	            <td>{{ $s_invtry->Multiplier }}</td>
	        </tr> 
	        @endforeach  
	    @endif

	</tbody>
</table>
<script>
$("#retailall").change(function(){
    if(this.checked){ 
        $(".retail").each(function(){
            this.checked=true;
        });         
    }else{
        $(".retail").each(function(){
            this.checked=false;
        });              
    }
});
$(function(){
    var isSelected = [];
    $('.retail').each(function() {
        if ($(this).is(":checked")) {
            isSelected.push("true");
        } else {
            isSelected.push("false");
        }
    });
    if($.inArray("false", isSelected) < 0) {
    	if(isSelected.length != 0){
    		$(".retail-all").attr("checked", true);
    	}	
	}
});
</script>