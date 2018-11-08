@foreach ($items as $item) 
  <tr>
    <td>{{$item->po_no}}</td>
    <td>{{$item->po_date}}</td>
    <td>
      {{ $item->template ? $item->template->po_tmpl8_desc : '' }}
    </td>
    <td>
      {{ $item->served == 1 ? 'Served' : 'Unserved' }}
    </td>
    <td style="text-align: center;">{{number_format($item->tot_pcs)}}</td>
    <td style="text-align: right;">{{number_format((float)$item->total_amt, 2)}}</td>
    <td>
      <a class="btn btn-primary btn-md {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V') ? "" : "disabled" }}" title="View PO Details"
        href="{{ route('stocktransfer.show', [$item, 'corpID' => $corpID]) }}">
          <span class="far fa-eye"></span>
      </a>
      <a class="btn btn-warning btn-md blue-tooltip {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V') ? "" : "disabled" }}" title="View original Details" 
        href="{{ route('stocktransfer.original', [$item, 'corpID' => $corpID]) }}">
        <span class="glyphicon glyphicon-inbox"></span>
      </a>
      @if($item->served == '0')
      <a class="btn btn-success btn-md blue-tooltip {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'E') ? "" : "disabled" }}" title="Edit"   
        onclick="markToserved(event, {{$item->po_no}})">
        <span class="glyphicon glyphicon-ok"></span>
      </a>
      @endif
    </td>
  </tr>
@endforeach