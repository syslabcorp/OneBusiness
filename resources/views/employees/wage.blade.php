<div class="tab-pane fade {{ $tab == 'wage' ? 'active in' : '' }}" id="wage" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V') || true)
  <div class="rown">
    @if($recommendItem)
    <div class="col-md-12" style="border: 1px solid #ccc; margin-bottom: 20px; padding: 10px;">
      <div class="rown">
        <div class="col-sm-9">
          Pending Recommendation: <strong>{{ $recommendItem->effective_date->format('m/d/Y') }}</strong> - 
          Form ({{ $recommendItem->fromTemplate->code }}) to ({{ $recommendItem->toTemplate->code }}) <br>
          By: {{ $recommendItem->recommendedBy ? $recommendItem->recommendedBy->UserName : '' }}
        </div>
        <div class="col-sm-3 text-right">
          <form action="{{ route('employee.deleteRecommendation', $user) }}" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="corpID" value="{{ $corpID }}">
            <input type="hidden" name="txn_no" value="{{ $recommendItem->txn_no }}">
            <button class="btn btn-default btn-md" type="submit">
              Cancel Recommendation
            </button>
          </form>
        </div>
      </div>
    </div>
    @else
    <div class="col-md-12 text-right">
      <button class="btn btn-primary btn-md" data-toggle="modal" data-target="#modal-new-recommendation">
        New Recommendation
      </button>
    </div>
    @endif
    <div class="table-responsive col-md-12">
    <table id="table-wage-deliveries" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Effective Date</th>
          <th>Base Rate</th>
          <th>Pay Code</th>
          <th>Pay Basis</th>
        </tr>
      </thead>
      <tbody >
    </tbody>
      </table>
    </div>
  </div>
  @else
  <div class="alert alert-danger no-close">
    You don't have permission
  </div>
  @endif
</div>
