<div class="tab-pane fade {{ $tab == 'stock' ? 'active in' : '' }}" id="shortages" >
  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
  <div class="row">
    <div class="col-md-9">
      <form action="" class="form">
        <div class="form-group">
          <div class="col-md-2">
            <label for="">From</label>
          </div>
          <div class="col-md-3">
            <input type="date" name="from_date" id="">
          </div>

          <div class="col-md-2">
            <label for="">To</label>
          </div>
          <div class="col-md-3">
            <input type="date" name="to_date" id="">
          </div>

          <div class="col-md-2">
            <button class="btn btn-primary">Show</button>
          </div>
        </div>
      </form>

      <div class="table-responsive">
        <table id="table-shortage-deliveries" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
          <thead>
            <tr>
              <th>Payroll Period</th>
              <th>Branch/Shift Date</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody >
          </tbody>
        </table>
      </div>

    </div>
    <div class="col-md-3">
      <p style="color:red">
        Total Shortage:
        <strong>-51</strong>
      </p>
    </div>
  </div>
  @else
  <div class="alert alert-danger no-close">
    You don't have permission
  </div>
  @endif
</div>
