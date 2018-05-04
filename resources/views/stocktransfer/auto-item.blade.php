@foreach ($items as $item) 
  <tr id="emp{{$item->po_no}}">
    <td>{{$item->po_no}}</td>
    <td>{{$item->po_date}}</td>
    <td>
      {{ $item->template ? $item->template->po_tmpl8_desc : '' }}
    </td>
    <td 
      @if($item->served==0)
      Unserved
      @elseif($item->served==1)
      Served
      @endif
    </td>
    <td  style="text-align: center;">{{number_format($item->tot_pcs)}}</td>
    <td  style="text-align: right;">{{number_format((float)$item->total_amt, 2)}}</td>
    <td  style="text-align: center;">
      <a class="btn btn-primary btn-md" title="View PO Details"
        href="{{ route('stocktransfer.show', [$item, 'corpID' => $corpID]) }}">
          <span class="glyphicon glyphicon-eye-open"></span>
      </a>
      <a class="btn btn-warning btn-md blue-tooltip " data-title="View original Details" 
        href="{{ route('tmaster.originaldetails',$item->po_no) }}" data-toggle="tooltip" data-placement="top" title="" data-original-title="view original detail">
        <span class="glyphicon glyphicon-inbox"></span>
      </a>
      <a class="btn btn-success btn-md blue-tooltip " data-title="Edit" onclick="markToserved({{$item->po_no}})"
        data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit Corporation">
        <span class="glyphicon glyphicon-ok"></span>
      </a>
    </td>
  </tr>
@endforeach