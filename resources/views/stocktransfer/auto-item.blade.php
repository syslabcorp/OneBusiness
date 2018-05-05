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
    <td>
      <a class="btn btn-primary btn-sm" title="View PO Details"
        {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V') ? "" : "disabled" }}
        href="{{ route('stocktransfer.show', [$item, 'corpID' => $corpID]) }}">
          <span class="glyphicon glyphicon-eye-open"></span>
      </a>
      <a class="btn btn-warning btn-sm blue-tooltip" title="View original Details" 
        {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V') ? "" : "disabled" }}
        href="{{ route('stocktransfer.original',$item->po_no) }}">
        <span class="glyphicon glyphicon-inbox"></span>
      </a>
      @if($item->served == '0')
      <a class="btn btn-success btn-sm blue-tooltip" title="Edit" 
        {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'E') ? "" : "disabled" }}
        onclick="confirm('Serve PO: Are you sure you want to mark {{ $item->po_no }} as served?') && markToserved({{$item->po_no}})">
        <span class="glyphicon glyphicon-ok"></span>
      </a>
      @endif
    </td>
  </tr>
@endforeach