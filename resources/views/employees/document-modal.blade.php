<div class="modal fade" id="modal-document">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('employee.storeDocument', $user) }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        @if ($docItem->txn_no)
          <input type="hidden" name="txn_no" value="{{ $docItem->txn_no }}">
          <input type="hidden" name="_method" value="PUT">
        @endif
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">{{ $docItem->txn_no ? 'Edit Document' : 'New Document' }}</h4>
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
                <select name="doc_no" required>
                  @foreach($categories as $cat)
                    <option {{ $docItem->doc_no == $cat->doc_no ? 'selected' : '' }} 
                      value="{{ $cat->doc_no }}">{{ $cat->description }}</option>
                  @endforeach
                </select>
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
                <select name="subcat_id" required>
                  <option value="">--Select--</option>
                  @foreach($subCategories as $subcat)
                    <option doc-no="{{ $subcat->doc_no }}" {{ $docItem->subcat_id == $subcat->subcat_id ? 'selected' : '' }} 
                      value="{{ $subcat->subcat_id }}">{{ $subcat->description }}</option>
                  @endforeach
                </select>
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
              <div class="rown">
                <div class="col-xs-1">
                  <div class="form-group" style="margin-top: 5px;">
                    <label>
                      <input type="checkbox" {{ $docItem->doc_exp && $docItem->doc_exp != '0000-00-00' ? 'checked' : ''}} onchange="toggleExpiry(event)">
                    </label>
                  </div>
                </div>
                <div class="col-xs-11">
                  <div class="form-group">
                    <input {{ $docItem->doc_exp && $docItem->doc_exp != '0000-00-00' ? '' : 'disabled' }} type="date" 
                      class="form-control" name="doc_exp" value="{{ $docItem->doc_exp }}">
                  </div>
                </div>
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
                <textarea name="notes" rows="3" class="form-control">{{ $docItem->notes }}</textarea>
              </div>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-4 text-right">
              <label>Image Filename:</label>
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <input type="file" class="form-control" name="photo" {{ !$docItem->img_file ? 'required' : '' }}>
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
                <select name="branch" class="form-control" required>
                  <option value="">--Select--</option>
                  @foreach($branches as $branch)
                  <option {{ $docItem->txn_no && $docItem->branch_id == $branch->Branch || $branch->isChecked ? 'selected' : '' }} 
                    value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="pull-left">
              <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-primary btn-create">{{ $docItem->txn_no ? 'Save' : 'Create' }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>