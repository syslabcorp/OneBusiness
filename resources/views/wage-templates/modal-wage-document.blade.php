<div class="modal fade modalWageDocument">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('wage-templates.document') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Setup Wage Document</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <div class="rown">
              <div class="col-sm-4 text-right">
                <label>Document Category:</label>
              </div>
              <div class="col-sm-7">
                <select class="form-control" name="wt_doc_cat">
                  <option value="">-- Select --</option>
                  @foreach($categories as $cat)
                    <option value="{{ $cat->doc_no }}" 
                    {{ $company->wt_doc_cat == $cat->doc_no ? 'selected' : '' }}>{{ $cat->description }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="rown">
              <div class="col-sm-4 text-right">
                <label>Subcategory:</label>
              </div>
              <div class="col-sm-7">
              <select class="form-control" name="wt_doc_subcat">
                <option value="">-- Select --</option>
                @foreach($subCategories as $cat)
                  <option data-cat="{{ $cat->doc_no }}" value="{{ $cat->subcat_id }}"
                    {{ $company->wt_doc_subcat == $cat->subcat_id ? 'selected' : '' }}
                    >{{ $cat->description }}</option>
                @endforeach
              </select>
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
              <button type="submit" class="btn btn-primary btn-save">Save</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>