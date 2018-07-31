<div class="tab-pane fade {{ $tab == 'stock' ? 'active in' : '' }}" id="wage" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
  <div class="row">
    <div class="table-responsive">
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
