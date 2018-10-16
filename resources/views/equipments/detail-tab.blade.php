@php $partItems = is_array(old('parts')) ? old('parts') : $equipment->details; @endphp
<div class="tab-pane fade {{ $tab == 'auto' ? 'active in' : '' }} in" id="equipDetail">
  <div class="row">
    @if($equipment->asset_id)
    <form class="form form-horizontal" action="{{ route('equipments.update', [$equipment, 'corpID' => request()->corpID]) }}" method="POST">
      <input type="hidden" name="_method" value="PUT">
    @else
    <form class="form form-horizontal" action="{{ route('equipments.store', ['corpID' => request()->corpID]) }}" method="POST">
    @endif
      {{ csrf_field() }}
      <div class="rown">
        <div class="col-sm-6">
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Asset #:</strong></label>
            </div>
            <div class="col-sm-9 form-group">
             <input type="text" class="form-control" readonly value="{{ $equipment->asset_id ?: (isset($lastAssetId) ? $lastAssetId : 1) }}" name="asset_id">
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
          <div class="rown">
            <div class="col-sm-3 form-group text-right">
              <label style="padding: 5px;"><strong>Active:</strong></label>
            </div>
            <div class="col-sm-9 form-group" style="margin-top: 5px;">
              <input type="checkbox" class="equipActive" name="active" value="1" {{ (old('active') ?: $equipment->isActive) ? 'checked' : '' }}
                onclick="{{ $equipment->asset_id ? 'return false;' : '' }}">
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
                <option {{ $equipment->type == 'Com Proper' ? 'selected' : '' }} value="Com Proper">Company Property</option>
                <option {{ $equipment->type == 'Rental' ? 'selected' : '' }} value="Rental">Rental</option>
              </select>
            </div>
          </div>
          <div class="rown">
            <div class="col-sm-5 form-group text-right">
              <label style="padding: 5px;"><strong>Branch:</strong></label>
            </div>
            <div class="col-sm-7 form-group">
              <select name="branch" class="form-control" data-branch="{{ $equipment->branch }}">
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
              <select name="dept_id" class="form-control" data-dept="{{ $equipment->dept_id }}">
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
              <select name="jo_dept" class="form-control" data-job="{{ $equipment->jo_dept }}">
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
      <h4>Hardware Information</h4>
      @if(!count($partItems))
      <p>
        No parts yet. 
        <a href="javascript:void(0)" class="addHere" onclick="openTablePart(event)" style="{{ $equipment->asset_id ? 'display: none;' : '' }}">
          Add here
        </a>
      </p>
      @endif

      @include('equipments.parts')
      <div class="rown">
        <div class="col-xs-6">
          <a class="btn btn-default" href="{{ route('equipments.index', ['corpID' => request()->corpID]) }}">Back</a>
        </div>
        <div class="col-xs-6 text-right">
          @if($equipment->asset_id)
            <button {{ \Auth::user()->checkAccessById(56, 'E') ? : 'disabled' }} type="button" class="btn btn-edit btn-info">
              <i class="fas fa-pencil-alt"></i> Edit
            </button>
            <button style="display: none;" class="btn btn-success btn-save"><i class="far fa-save"></i> Save</button>
          @else
            <button class="btn btn-success btn-save" {{ \Auth::user()->checkAccessById(56, 'A') ? : 'disabled' }} >
              <i class="far fa-save"></i> Create
            </button>
          @endif
        </div>
      </div>
    </form>
  </div>
</div>
      