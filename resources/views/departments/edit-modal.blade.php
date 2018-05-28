<div class="modal fade modalEditDepartment">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('departments.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="corpID" value="{{ request()->corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">
            Edit <span class="departmentName"></span>
          </h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="rown">
              <div class="col-sm-3 text-right">
                <label>Department:</label>
              </div>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="department">
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="rown">
              <div class="col-sm-3 text-right">
                <label>Main:</label>
              </div>
              <div class="col-sm-8">
                <input type="checkbox" name="main" value="1">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="rown">
            <div class="col-xs-6 text-left">
              <button type="button" class="btn btn-default" data-dismiss="modal">
                <i class="fas fa-reply"></i>
                Back
              </button>
            </div>
            <div class="col-xs-6 text-right">
              <button type="submit" class="btn btn-success btn-save">Save</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>