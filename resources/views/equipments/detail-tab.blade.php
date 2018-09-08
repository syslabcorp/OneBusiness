<div class="tab-pane fade {{ $tab == 'auto' ? 'active in' : '' }} in" id="personInfo" >
  <div class="row">
    <form class="form form-horizontal" action="{{ route('equipments.store', ['corpID' => request()->corpID]) }}" method="POST">
      {{ csrf_field() }}
      <div class="rown">
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Asset #:</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="text" class="form-control" readonly>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Equipment:</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="text" class="form-control" name="description" 
                value="{{ old('description') ?: $equipment->description }}">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Vendor:</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <select name="supplier_id">
                <option value="">-- Select --</option>
                @foreach($vendors as $item)
                <option value="{{ $item->Supp_ID }}">{{ $item->VendorName }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Quantity:</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="number" class="form-control">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Total Cost:</strong></label>
            </div>
            <div class="col-sm-9 form-group">
              <input type="number" class="form-control" readonly>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Type:</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <select name="type" class="form-control">
                <option value="">-- Select --</option>
                <option value="Company Property">Company Property</option>
                <option value="Rental">Rental</option>
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Branch:</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <select name="branch" id="" class="form-control">
                <option value="">-- Select --</option>
                @foreach($branches as $branch)
                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Department:</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <select name="dept_id" id="" class="form-control">
                <option value="">-- Select --</option>
                @foreach($deptItems as $item)
                <option value="{{ $item->dept_ID }}">{{ $item->department }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Job order Department:</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <select name="jo_dept" class="form-control">
                <option value="">-- Select --</option>
                @foreach($deptItems as $item)
                <option value="{{ $item->dept_ID }}">{{ $item->department }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <h5>Hardware Information</h5>

      <div class="rown">
        <div class="col-xs-6">
          <a class="btn btn-default" href="{{ route('equipments.index', ['corpID' => request()->corpID]) }}">Back</a>
        </div>
        <div class="col-xs-6 text-right">
          <button class="btn btn-success"><i class="far fa-save"></i> Save</button>
        </div>
      </div>
    </form>
  </div>
</div>
      