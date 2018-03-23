<table id="pro_line" class="table table-striped table-bordered" cellspacing="0" width="100%">
	<thead>
		<tr>
	        <th><input class="retail-all" type="checkbox" name="retailall" id="retailall"></th>
	        <th>Item Code</th>
	        <th>Brand</th>
	        <th>Pkg</th>
	        <th>Thresh</th>
	        <th>Mult</th>
	    </tr> 
	</thead>
	<tbody>
		@if(empty($s_invtry_hdr))
		<tr><td colspan="6">No Retail Item Exists !!</td></tr> 
		@else
			@foreach ($s_invtry_hdr as $s_invtry) 
	        <tr>
	            <td><input class="retail retailidArray" type="checkbox" name="item_id[]" id="item_id" value="{{ $s_invtry->item_id }}" onchange="enablecheckbox()" {{ (isset($proitems_ids) && in_array($s_invtry->item_id, $proitems_ids)) ? "checked" : "" }} {{ (isset($retail_itemsArray) && in_array($s_invtry->item_id, $retail_itemsArray)) ? "checked" : "" }}></td>
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
        	retail_itemsArray.push($(this).val());   
            this.checked=true;
        });         
    }else{
        $(".retail").each(function(){
        	retail_itemsArray.pop($(this).val());
            this.checked=false;
        });              
    }
    enablecheckbox();  
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
$('.retailidArray').change(function(){ 
    if ($(this).is(":checked")) {
        retail_itemsArray.push($(this).val());   
    } else {
        retail_itemsArray.pop($(this).val());
    }
});
</script>