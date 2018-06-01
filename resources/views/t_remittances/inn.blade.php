<div style="margin-bottom: 30px;">
  <form action="{{ route('branch_remittances.show', [$collection, 'corpID' => $company->corp_id]) }}"
    id="status-filter">
    <input type="hidden" name="corpID" value="{{ $company->corp_id }}"/>
    <div class="row">
      <div class="form-group">
        <label for="" class="control-label col-xs-2">
          CLEAR STATUS
        </label>
        <div class="col-xs-10">
          <label class="radio-inline" for="status_all">
            <input type="radio" name="status" id="status_all" value="all"
              {{ $queries['status'] == 'all' ? "checked" : "" }}>
            All
          </label>
        
          <label class="radio-inline" for="status_checked">
            <input type="radio" name="status" id="status_checked" value="1"
              {{ $queries['status'] == '1' ? "checked" : "" }}>
            Checked
          </label>

          <label class="radio-inline" for="status_unchecked">
            <input type="radio" name="status" id="status_unchecked" value="0"
              {{ $queries['status'] == '0' ? "checked" : "" }}>
            Unchecked
          </label>
          <label class="radio-inline" for="shortage_only" style="padding-left: 50px;">
            <input type="checkbox" name="shortage_only" id="shortage_only" value="1"
              {{ $queries['shortage_only'] == '1' ? "checked" : "" }}>
            Show Shortage only
          </label>

          <label class="radio-inline" for="remarks_only" style="padding-left: 0px;">
            <input type="checkbox" name="remarks_only" id="remarks_only" value="1"
              {{ $queries['remarks_only'] == '1' ? "checked" : "" }}>
            Show Remarks only
          </label>
        </div>
      </div>
    </div>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-striped table-bordered table-remittances">
    <thead>
      <tr>
        <th class="text-center">BRANCH</th>
        <th class="text-center">DATE</th>
        <th class="text-center">SHIFT ID</th>
        <th class="text-center">SHIFT TIME</th>
        <th class="text-center">CASHIER NAME</th>
        <th class="text-center">ROOMS</th>
        <th class="text-center">ORDERS</th>
        <th class="text-center">TOTAL SALES</th>
        <th class="text-center">TOTAL REMIT</th>
        <th class="text-center">CLR</th>
        <th class="text-center">WI</th>
        <th class="text-center">AS</th>
        <th class="text-center">SHORT</th>
        <th class="text-center">REMARKS</th>
        <th class="text-center">ACTION</th>
      </tr>
    </thead>
    <tbody>
      @php $totalShifts = 0 @endphp
      @foreach($collection->details()->get() as $detail)
        @foreach($detail->shifts($company->corp_id, $queries) as $branch => $shifts_by_date)
          @php $index_branch = $loop->index @endphp

          @php $count = 0 @endphp
          @foreach($shifts_by_date as $date => $shifts)
            @php $count += count($shifts); $totalShifts += count($shifts); @endphp
          @endforeach
          
          @foreach($shifts_by_date as $date => $shifts)
            @php $index = $loop->index @endphp
            @foreach($shifts as $shift)
              <tr data-branch="{{ $shift->branch->Branch }}" data-date="{{ $date }}" data-id="{{ $shift->Shift_ID }}">
                @if($index == 0 && $loop->index == 0)
                  <td class="col-branch" rowspan="{{$count}}">{{$shift->branch->ShortName}}</td>
                @endif
                @if($loop->index == 0 )
                  <td class="col-date" rowspan="{{count($shifts)}}">{{$date}}</td>
                @endif
                <td>{{ str_pad($shift->Shift_ID, 8, "0", STR_PAD_LEFT) }}</td>
                <td>{{ date("h:i A", strtotime($shift->shift_start) ) }}</td>
                <td>{{ $shift->user ? $shift->user->UserName : "" }}</td>
                <td class="col-retail text-right">
                  {{ $shift->remittance ? number_format($shift->remittance->TotalRoom, 2) : "" }}
                </td>
                <td class="col-service text-right">
                  {{ $shift->remittance ? number_format($shift->remittance->TotalOrders, 2) : "" }}
                </td>
                <td class="col-rental text-right">
                  {{ $shift->remittance ? number_format($shift->remittance->TotalSales, 2) : "" }}
                </td>
                <td class="col-internet text-right">
                  {{ $shift->remittance ? number_format($shift->remittance->TotalRemit, 2) : "" }}
                </td>
                <td class="col-clr">
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Sales_Checked == 1 ? "checked" : "") : "" }} onclick="return false;" >
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Wrong_Input == 1 ? "checked" : "") : "" }} onclick="return false;" >
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Adj_Short == 1 ? "checked" : "") : "" }} onclick="return false;"  >
                <td class="text-right">
                  @if($shift->remittance->Adj_Short == 1)
                    {{ number_format($shift->remittance->Adj_Amt, 2) }}
                  @else
                    {{ number_format(($shift->remittance->TotalSales - $shift->remittance->TotalRemit)*-1 , 2) }}
                  @endif
                </td>
                <td>{{ $shift->remittance ? $shift->remittance->Notes : "" }}</td>
                <td>
                  <button type="button" class="btn btn-primary show_modal {{ \Auth::user()->checkAccessByIdForCorp($company->corp_id, 15, 'E') ? "" : "disabled" }}" 
                    data-shift-id="{{$shift->Shift_ID}}" 
                    data-toggle="modal" data-target="#Modal" data-corp="{{ $company->corp_id }}">
                    <i class="fas fa-pencil-alt"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @endforeach
        @endforeach
      @endforeach
      @if($totalShifts == 0)
      <tr>
        <td colspan="15">
          No items
        </td>
      </tr>
      @endif
      <tr class="remitance-total" style="pointer-events: none;">
        <td colspan="5"><strong>Auto Total:</strong></td>
        <td class="text-right"><span class="total">0</span></td>
        <td class="text-right"><span class="total">0</span></td>
        <td class="text-right"><span class="total">0</span></td>
        <td class="text-right"><span class="total">0</span></td>
        <td colspan="6"></td>
      </tr>
    </tbody>
  </table>
</div>

@section('pageJS')
<script type="text/javascript">
$(document).ready(function(){
  $('.table-remittances td').each(function(el, index) {
    if(parseInt($(this).text()) == 0) {
      $(this).css('color', 'red');
    }
  });

  $('#status-filter input[name="status"], \
    #status-filter input[name="shortage_only"], \
    #status-filter input[name="remarks_only"]').change(function(event) {
    $(this).parents('form').submit();
  });
});
</script>
@endsection