<!-- Large modal -->
<div class="modal fade create-part-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
  <div class="modal-dialog" role="document">
    <form action="{{ route('parts.store') }}" method="POST">
      {{ csrf_field() }}
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="gridSystemModalLabel">Add a Part</h4>
            </div>
            <div class="modal-body">
                <div class="rown">
                  <div class="col-sm-3 text-right">
                      <label for="name">Part Name:</label> 
                  </div>
                  <div class="col-sm-8">
                      <input type="text" class="form-control" id="">
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
                      <option value="{{ $brand->brand_id }}">{{ $brand->description }}</option>
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
                    <select name="category_id" class="form-control">
                      @foreach($categories as $category)
                      <option value="{{ $category->cat_id }}">{{ $category->description }}</option>
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
                    <select name="category_id" class="form-control">
                      @foreach($vendors as $vendor)
                      <option value="{{ $vendor->Supp_ID }}">{{ $vendor->VendorName }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <br>
                
                <div class="rown">
                  <div class="col-sm-3 col-md-3">
                    <label for="">Consumable</label>  <input type="checkbox" value="">
                  </div>
                  <div class="col-sm-3">
                    <label for="">With Serial No</label>  <input type="checkbox" value="">
                  </div>
                  <div class="col-sm-3">
                    <label for="">Active</label>  <input type="checkbox" value="">
                  </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </form>
  </div>
</div>
