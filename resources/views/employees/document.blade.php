<div class="tab-pane fade {{ $tab == 'stock' ? 'active in' : '' }}" id="document" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
  <div class="row">
    <div class="table-responsive">
    <table id="table-document-deliveries" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>DOC #</th>
          <th>Series #</th>
          <th>Approval #</th>
          <th>Branch</th>
          <th>Category</th>
          <th>Document</th>
          <th>Notes</th>
          <th>Expiry</th>
          <th>Image file</th>
          <th>Date Archived</th>
          <th>Action</th>
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
