<div class="modal fade edit-part-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog" role="document">
    <form action="{{ route('parts.update', $item) }}" method="POST">
      {{ csrf_field() }}
      <input type="hidden" name="_method" value="PUT">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Edit a Part</h4>
            </div>
            <div class="modal-body">
                <div class="rown">
                  <div class="col-sm-3 text-right">
                      <label for="name">Part Name:</label> 
                  </div>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" name="description" value="{{ $item->description }}" required>
                  </div>
                </div>
                <br>
                <div class="rown">
                  <div class="col-sm-3 text-right">
                      <label for="name">Brand:</label> 
                  </div>
                  <div class="col-sm-8">
                    <select name="brand_id" class="form-control">
                      @foreach($brands as $brand)
                      <option value="{{ $brand->brand_id }}" 
                        {{ $item->brand_id == $brand->brand_id ? 'selected' : '' }}>{{ $brand->description }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <br>
                <div class="rown">
                  <div class="col-sm-3 text-right">
                      <label for="name">Category:</label> 
                  </div>
                  <div class="col-sm-8">
                    <select name="cat_id" class="form-control">
                      @foreach($categories as $category)
                      <option value="{{ $category->cat_id }}"
                      {{ $item->cat_id == $category->cat_id ? 'selected' : '' }}>{{ $category->description }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <br>
                <div class="rown">
                  <div class="col-sm-3 text-right">
                      <label for="name">Vendor:</label> 
                  </div>
                  <div class="col-sm-8">
                    <select name="supplier_id" class="form-control">
                      @foreach($vendors as $vendor)
                      <option value="{{ $vendor->Supp_ID }}"
                      {{ $item->supplier_id == $vendor->Supp_ID ? 'selected' : '' }}>{{ $vendor->VendorName }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <br>
                
                <div class="rown">
                  <div class="col-sm-3 col-md-offset-3">
                    <label for="">Consumable</label>  <input type="checkbox" value="1" name="consumable" {{ $item->consumable == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="col-sm-3">
                    <label for="">With Serial No</label>  <input type="checkbox" value="1" name="with_serialno" {{ $item->with_serialno == 1 ? 'checked' : '' }}>
                  </div>
                  <div class="col-sm-3">
                    <label for="">Active</label>  <input type="checkbox" value="1" name="isActive" {{ $item->isActive == 1 ? 'checked' : '' }}>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </form>
  </div>
</div>
