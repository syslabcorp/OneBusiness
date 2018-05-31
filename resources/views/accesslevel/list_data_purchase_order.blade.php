@if($s_po_tmpl8->isEmpty())
<tr><td colspan="4">No Template Exists.</td></tr> 
@else
	@foreach ($s_po_tmpl8 as $s_template) 
    <tr>
        <td>{{ $s_template->po_tmpl8_desc }}</td>
        <td>{{ $s_template->po_avg_cycle }}</td>
        <td><input class="retail" type="checkbox"<?php if($s_template->active == 1){ echo 'checked'; }else{echo '';}?> disabled></td>
        <td>
        	<a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessByPoId([$corp_id],31, 'E') ? '' : 'disabled' }}" data-title="Edit" href="{{ URL::to('purchase_order/'.(isset($corp_id) ? $corp_id : 0).'/'.$s_template->city_id.'/'.$s_template->po_tmpl8_id ) }}" data-toggle="tooltip" data-placement="top" title="Edit Template"><span class="fas fa-pencil-alt"></span></a>
        </td>
    </tr> 
    @endforeach  
@endif
