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

          <label class="radio-inline" for="">
            <input type="radio" name="status" id="">
            Show Shortage only
          </label>

          <label class="radio-inline" for="">
            <input type="radio" name="status" id="">
            Show Remarks only
          </label>
          </div>
      </div>
    </div>
  </form>
</div>

<div class="table-responsive">
  <table class="table table-striped table-bordered">
    <tbody>
      <tr>
        <th class="text-center">BRANCH</th>
        <th class="text-center">DATE</th>
        <th class="text-center">SHIFT ID</th>
        <th class="text-center">SHIFT TIME</th>
        <th class="text-center">CASHIER NAME</th>
        <th class="text-center">DETAIL</th>
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

      @foreach($collection->details()->get() as $detail)
        @foreach($detail as $date => $shifts)
          @php $index = $loop->index @endphp
          @foreach($shifts as $shift)
            <tr>
              @if($index == 0 && $loop->index == 0)
                <td rowspan="{{$count}}">{{$branch}}</td>
              @endif
              @if($loop->index == 0 )
                <td rowspan="{{count($shifts)}}">{{$date}}</td>
              @endif
              <td>{{ $shift->Shift_ID }}</td>
              <td>{{ date("h:i A", strtotime($shift->ShiftTime) ) }}</td>
              <td></td>
              <td></td>
              <td>{{ $shift->remittance ? $shift->remittance->Serv_TotalSales : "" }}</td>
              <td>{{ $shift->remittance ? $shift->remittance->Games_TotalSales : "" }}</td>
              <td>{{ $shift->remittance ? $shift->remittance->Net_TotalSales : "" }}</td>
              <td>{{ $shift->remittance ? $shift->remittance->TotalSales : "" }}</td>
              <td>{{ $shift->remittance ? $shift->remittance->TotalRemit : "" }}</td>
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
                <button type="button" class="btn btn-primary show_modal" data-shift-id="{{$shift->Shift_ID}}" data-toggle="modal" data-target="#Modal">
                  <i class="fa fa-pencil"></i>
                </button>
              </td>
            </tr>
              
          @endforeach
        @endforeach
      @endforeach

    </tbody>
  </table>
</div>