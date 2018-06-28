<div class="tab-pane fade {{ $tab == 'stock' ? 'active in' : '' }}" id="position" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
  <div class="row">
    <div class="table-responsive">
    <table id="table-position-deliveries" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Branch</th>
          <th>Start Date</th>
          <th>Separation Date</th>
          <th>Position</th>
          <th>Status</th>
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
