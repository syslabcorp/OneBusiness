<div class="tab-pane fade {{ $tab == 'tardiness' ? 'active in' : '' }}" id="tardiness" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
  <div class="row">
    <div class="col-md-9">
      <form action="{{ route('employee.show', [$user]) }}">
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <input type="hidden" name="tab" value="tardiness">
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
          <div class="col-md-2 form-group">
            <button class="btn btn-primary" type="submit">Show</button>
            <button class="btn btn-md btn-info" type="button" onclick="window.print()">
              Print
            </button>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        @if(request()->from_date && request()->to_date && $tab == 'tardiness')
        <h3 class="print">Name: <strong>{{ $user->UserName }}</strong></h3>
        <table class="table table-striped table-bordered tardiness-datatable" cellspacing="0" width="100%">
          <tbody>
            @foreach($tardinessItems as $periodItems)
              <tr>
                <td class="text-center"  data-order="{{ $periodItems->first()->order }}">
                  {{ $periodItems->first()->period }}
                </td>
                <td class="text-center">
                  {{ $periodItems->first()->branch ? $periodItems->first()->branch->ShortName : '' }}
                </td>
                <td class="text-right">
                  {{ $periodItems->sum('late_hrs')*60 }}
                </td>
              </tr>
              @foreach($periodItems as $shift)
              <tr>
                <td class="text-center" data-order="{{ $shift->order }}">
                  {{ $shift->period }}
                </td>
                <td class="text-center">
                  {{ (new DateTime($shift->TimeIn))->format('m/d/Y') }}
                </td>
                <td class="text-right">
                  {{ $shift->late_hrs*60 }}
                </td>
              </tr>
              @endforeach
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
  </div>
  @else
  <div class="alert alert-danger no-close">
    You don't have permission
  </div>
  @endif
</div>
