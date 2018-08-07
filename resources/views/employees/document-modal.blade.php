<div class="modal fade" id="modal-document">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('employee.recommendation', $user) }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title"></h4>
        </div>
        <div class="modal-body">
          <div class="rown">
            <div class="col-sm-4">
              <div class="form-group text-right">
                <label>Document Type:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <input type="text" class="form-control" >
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <div class="form-group">
                <label>Subcategory:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <input type="text" class="form-control" >
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <div class="form-group">
                <label>Expiry:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <select name="to_wage" class="form-control">
                </select>
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <div class="form-group">
                <label>Notes:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <textarea name="" rows="3" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <label>Image Filename:</label>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <input type="date" class="form-control" name="effective_date" required>
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <div class="form-group">
                <label>Branch:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <select name="to_wage" class="form-control">
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="pull-left">
              <button type="button" class="btn btn-default" data-dismiss="modal"> Back</button>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-primary btn-create">Create</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>