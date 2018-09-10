<div class="tab-pane fade {{ $tab == 'auto' ? 'active in' : '' }} in" id="equipmentDetail" >
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
             <input type="text" class="form-control" readonly value="{{ $equipment->asset_id }}">
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Equipment:</strong></label>
            </div>
            <div class="col-sm-9 form-group {{ $errors->has('description') ? 'has-error' : ''}}">
              <input type="text" class="form-control" name="description" 
                value="{{ old('description') ?: $equipment->description }}">
              @if($errors->has('description'))
                <span class="help-block">{{ $errors->first('description') }}</span>
              @endif
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
                <option {{ $equipment->type == 'Com Proper' ? 'selected' : '' }} value="Com Proper">Com Proper</option>
                <option {{ $equipment->type == 'Rental' ? 'selected' : '' }} value="Rental">Rental</option>
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Branch:</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <select name="branch" class="form-control">
                @foreach($branches as $branch)
                <option {{ $equipment->branch == $branch->Branch ? 'selected' : '' }} value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
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
                <option value="">-- select --</option>
                @foreach($deptItems as $item)
                <option {{ $equipment->dept_id == $item->dept_ID ? 'selected' : '' }} value="{{ $item->dept_ID }}">{{ $item->department }}</option>
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
                <option value="">-- select --</option>
                @foreach($deptItems as $item)
                <option {{ $equipment->jo_dept == $item->dept_ID ? 'selected' : '' }} value="{{ $item->dept_ID }}">{{ $item->department }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
      </div>
      <hr>
      <h5>Hardware Information</h5>
      <p>No parts yet. <a href="javascript:void(0)" onclick="openTablePart(event)">Add here</a></p>
      @include('equipments.parts')
      <div class="rown">
        <div class="col-xs-6">
          <a class="btn btn-default" href="{{ route('equipments.index', ['corpID' => request()->corpID]) }}">Back</a>
        </div>
        <div class="col-xs-6 text-right">
          @if($equipment->asset_id)
            <button class="btn btn-info"><i class="fas fa-pencil-alt"></i> Edit</button>
          @else
            <button class="btn btn-success"><i class="far fa-save"></i> Save</button>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>
      