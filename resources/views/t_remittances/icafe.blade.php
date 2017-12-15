<div style="margin-bottom: 30px;">
  <form action="">
    <div class="row">
      <div class="form-group">
        <label for="" class="control-label col-xs-2">
          CLEAR STATUS
        </label>
        <div class="col-xs-10">
          <label class="radio-inline" for="">
            <input type="radio" name="status" id="">
            All
          </label>
        
          <label class="radio-inline" for="">
            <input type="radio" name="status" id="">
            Checked
          </label>

          <label class="radio-inline" for="">
            <input type="radio" name="status" id="">
            Unchecked
          </label>
          <div class="form-group">
            <label class="radio-inline" for="shortage_only" style="padding-left: 0px;">
              <input type="checkbox" name="shortage_only" id="shortage_only">
              Show Shortage only
            </label>

            <label class="radio-inline" for="remarks_only" style="padding-left: 0px;">
              <input type="checkbox" name="remarks_only" id="remarks_only">
              Show Remarks only
            </label>
          </div>
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
        <th class="text-center">RETAIL</th>
        <th class="text-center">SERVICES</th>
        <th class="text-center">GAMES</th>
        <th class="text-center">INTERNET</th>
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
      @foreach($collection->details()->get() as $detail)
        @foreach($detail->shifts($company->corp_id) as $branch => $shifts_by_date)
          @php $index_branch = $loop->index @endphp

          @php $count = 0 @endphp
          @foreach($shifts_by_date as $date => $shifts)
            @php $count += count($shifts) @endphp
          @endforeach
          
          @foreach($shifts_by_date as $date => $shifts)
            @php $index = $loop->index @endphp
            @foreach($shifts as $shift)
              <tr data-branch="{{ $shift->branch->Branch }}" data-date="{{ $date }}">
                @if($index == 0 && $loop->index == 0)
                  <td class="col-branch" rowspan="{{$count}}">{{$shift->branch->ShortName}}</td>
                @endif
                @if($loop->index == 0 )
                  <td class="col-date" rowspan="{{count($shifts)}}">{{$date}}</td>
                @endif
                <td>{{ $shift->Shift_ID }}</td>
                <td>{{ date("h:i A", strtotime($shift->ShiftTime) ) }}</td>
                <td></td>
                <td class="col-retail">
                  {{ $shift->remittance ? round($shift->remittance->Sales_TotalSales, 2) : 0 }}
                </td>
                <td class="col-service">{{ $shift->remittance ? round($shift->remittance->Serv_TotalSales, 2) : 0 }}</td>
                <td>{{ $shift->remittance ? round($shift->remittance->Games_TotalSales, 2) : 0 }}</td>
                <td>{{ $shift->remittance ? round($shift->remittance->Net_TotalSales, 2) : 0 }}</td>
                <td class="col-sale">{{ $shift->remittance ? round($shift->remittance->TotalSales, 2) : 0 }}</td>
                <td class="col-remit">{{ $shift->remittance ? round($shift->remittance->TotalRemit, 2) : 0 }}</td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Sales_Checked == 1 ? "checked" : "") : "" }} onclick="return false;" >
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Wrong_Input == 1 ? "checked" : "") : "" }} onclick="return false;" >
                </td>
                <td>
                  <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Adj_Short == 1 ? "checked" : "") : "" }} onclick="return false;"  >
                <td></td>
                <td>{{ $shift->remittance ? $shift->remittance->Notes : "" }}</td>
                <td>
                  <button type="button" class="btn btn-primary show_modal" data-shift-id="{{$shift->Shift_ID}}" 
                    data-toggle="modal" data-target="#Modal" data-corp="{{ $company->corp_id }}">
                    <i class="fa fa-pencil"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @endforeach
        @endforeach
      @endforeach
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
});
</script>
@endsection