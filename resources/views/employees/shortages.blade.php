<div class="tab-pane fade {{ $tab == 'shortages' ? 'active in' : '' }}" id="shortages" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
  <div class="row">
    <div class="col-md-9">
      <form action="{{ route('employee.show', [$user]) }}">
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <input type="hidden" name="tab" value="shortages">
        <div class="row">
          <div class="col-md-1 form-group">
            <strong style="padding-top: 8px;display: block;">From:</strong>
          </div>
          <div class="col-md-3 form-group">
            <input type="date" name="from_date" class="form-control" value="{{ request()->from_date }}">
          </div>
          <div class="col-md-1 form-group">
            <strong style="padding-top: 8px;display: block;">To:</strong>
          </div>
          <div class="col-md-3 form-group">
            <input type="date" name="to_date" class="form-control" value="{{ request()->to_date }}">
          </div>
          <div class="col-md-1 form-group">
            <button class="btn btn-primary" type="submit">Show</button>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        @if(request()->from_date && request()->to_date && $tab == 'shortages')
        <table class="col-sm-12 table table-striped table-bordered shortages-datatable" cellspacing="0" width="100%">
          <tbody>
            @foreach($shortageItems as $shift)
            <tr>
              <td class="text-center">
                {{ $shift->period }}
              </td>
              <td class="text-center">
                {{ (new DateTime($shift->ShiftDate))->format('m/d/Y') }}
              </td>
              <td class="text-right">
                {{ $shift->remittance ? number_format($shift->remittance->Adj_Amt, 2) : '0.00' }}
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
          <div>
            Please specify date range
          </div>
        @endif
      </div>

    </div>
    <div class="col-md-3">
      <p style="color:red">
        Total Shortage:
        <strong>{{ number_format($shortageItems->sum(function($shift) { return $shift->remittance ? $shift->remittance->Adj_Amt : 0; }), 2) }}</strong>
      </p>
    </div>
  </div>
  @else
  <div class="alert alert-danger no-close">
    You don't have permission
  </div>
  @endif
</div>
