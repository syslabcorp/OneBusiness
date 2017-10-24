@if($s_po_tmpl8->isEmpty())
<tr><td colspan="4">No Template Exists.</td></tr> 
@else
	@foreach ($s_po_tmpl8 as $s_template) 
    <tr>
        <td>{{ $s_template->po_tmpl8_desc }}</td>
        <td>{{ $s_template->po_avg_cycle }}</td>
        <td><input class="retail" type="checkbox"<?php if($s_template->active == 1){ echo 'checked'; }else{echo '';}?> disabled></td>
        <td>
        	<a class="btn btn-primary btn-md blue-tooltip" data-title="Edit" href="{{ URL::to('purchase_order/'.$s_template->po_tmpl8_id ) }}" data-toggle="tooltip" data-placement="top" title="Edit Template"><span class="glyphicon glyphicon-pencil"></span></a>
        </td>
    </tr> 
    @endforeach  
@endif
