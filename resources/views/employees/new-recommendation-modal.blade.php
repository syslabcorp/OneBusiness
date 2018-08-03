<div class="modal fade" id="modal-new-recommendation">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('employee.recommendation', $user) }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <input type="hidden" name="from_wage" value="{{ $template ? $template->wage_tmpl8_id : '' }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Recommendation for {{ $user->UserName }}</h4>
        </div>
        <div class="modal-body">
          <div class="rown">
            <div class="col-sm-4">
              <div class="form-group text-right">
                <label>Current Position:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <input type="text" class="form-control" disabled value="{{ $template ? $template->position : '' }}">
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <div class="form-group">
                <label>Current Wage template:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <input type="text" class="form-control" disabled value="{{ $template ? $template->code : '' }}">
              </div>
            </div>
          </div>
          <div class="rown form-group">
            <div class="col-sm-3 text-right" style="color: red;">Recommend to</div>
            <div class="col-sm-9">
              <hr style="border-color: red; margin: 10px 0px;">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <div class="form-group">
                <label>New Wage template:</label>
              </div>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <select name="to_wage" class="form-control">
                  @foreach($templates as $temp)
                  <option value="{{ $temp->wage_tmpl8_id }}"
                    {{ $template && $template->code == $temp->code ? 'disabled' : '' }}> {{ $temp->code }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <label>Effective Date:</label>
            </div>
            <div class="col-sm-8">
              <input type="date" class="form-control" name="effective_date" required>
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