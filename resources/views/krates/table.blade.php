<div class="col-md-12">
  <div class="table-responsive">
    <table class="table borderred">
      <thead>
          <tr>
          <th>Room</th>
          <th>1Hr</th>
          @for($i = 2; $i <= 24; $i++)
          <th>{{ $i }}Hrs</th>
          @endfor
          <th>MinChrg</th>
          <th>nKey</th>
          </tr>
      </thead>
      <tbody>
        @foreach($rate->details()->orderBy('nKey', 'ASC')->get() as $detail)
          <tr>
            <td>
              {{ $loop->index + 1 }}
            </td>
            @for($i = 1; $i <= 24; $i++)
            <td>
                <input style="width: 60px" type="text" class="form-control" value="{{ $detail["Hr_{$i}"] }}" name="detail[{{ $detail->nKey }}][Hr_{{ $i }}]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
            </td>
            @endfor
            <td>
              <input style="width: 60px" type="text" class="form-control" value="{{ $detail->MinAmt1 }}" name="detail[{{ $detail->nKey }}][MinAmt1]" {{ \Auth::user()->checkAccessById(2, "E") ? "" : "disabled" }} readonly="true">
            </td>
            <td>
              {{ $detail->nKey }}
            </td>
            </tr>
          @endforeach
          @if($rate->details()->count() == 0)
          <tr>
            <td colspan="7"><strong>No data to display</strong></td>
          </tr>
          @endif
      </tbody>
    </table>
  </div>
</div>
@if($rate->tmplate_id && \Auth::user()->checkAccessById(2, "E"))
<div class="box-assign nohide">
  <hr>
  <div class="col-md-12">
    <div class="table-responsive">
      <table class="table borderred">
        <thead>
          <tr>
            <th></th>
            <th>1Hr</th>
            @for($i = 2; $i <= 24; $i++)
            <th>{{ $i }}Hrs</th>
            @endfor
            <th>MinChrg</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <button class="btn btn-sm btn-success" type="button">
                <i class="fa fa-magic"></i>
              </button>
            </td>
            @for($i = 1; $i <= 25; $i++)
            <td>
              <input style="width: 60px" type="text" step="any" class="form-control" placeholder="0.00">
            </td>
            @endfor
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@endif
