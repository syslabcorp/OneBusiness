<form action="{{ route('branchs.update', [$branch]) }}" method="POST" class="col-md-12 form-horizontal" novalidate>
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">
    <div class="form-group {{ $errors->has('branch_name') ? 'has-error' : '' }}">
        <label class="col-sm-2 control-label">Branch Name</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Branch Name" name="branch_name" value="{{ $branch->branch_name }}">
            @if($errors->has('branch_name'))
            <span class="help-block">{{ $errors->first('branch_name') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('operator') ? 'has-error' : '' }}">
        <label class="col-sm-2 control-label">Operator</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Operator" name="operator" value="{{ $branch->description }}">
            @if($errors->has('operator'))
            <span class="help-block">{{ $errors->first('operator') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('street') ? 'has-error' : '' }}">
        <label class="col-sm-2 control-label">Street Address</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" placeholder="Street Address" name="street" value="{{ $branch->street }}">
            @if($errors->has('street'))
            <span class="help-block">{{ $errors->first('street') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('province') ? 'has-error' : '' }}">
        <label class="col-sm-2 control-label">Province</label>
        <div class="col-sm-10">
            <select name="province" id="select-province" class="form-control">
                <option selected>Select Province</option>
                @foreach(\App\Province::all() as $province)
                    <option value="{{ $province->id }}" {{ $branch->city->province->id == $province->id ? 'selected' : ''}}>{{ $province->name }}</option>
                @endforeach
            </select>
            @if($errors->has('province'))
            <span class="help-block">{{ $errors->first('province') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('city') ? 'has-error' : '' }}">
        <label class="col-sm-2 control-label">City</label>
        <div class="col-sm-10">
            <select name="city" id="select-city" class="form-control">
                <option selected>Select City</option>
                @foreach(\App\City::all() as $city)
                    <option data-province="{{ $city->province_id }}" value="{{ $city->id }}"
                        {{ $branch->city->id == $city->id ? 'selected' : ''}}>{{ $city->name }}</option>
                @endforeach
            </select>
            @if($errors->has('city'))
            <span class="help-block">{{ $errors->first('city') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group {{ $errors->has('units') ? 'has-error' : '' }}" >
        <label class="col-sm-2 control-label">No. Of Units</label>
        <div class="col-sm-10">
            <input type="number" name="units" class="form-control" placeholder="No. Of Units" value="{{ $branch->max_units }}">
            @if($errors->has('units'))
            <span class="help-block">{{ $errors->first('units') }}</span>
            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label">Active</label>
        <div class="col-sm-10">
            <div class="control-checkbox">
                <input type="checkbox" id="brand-active" name="active" {{ $branch->active == 1 ? 'checked' : ''}} value="1">
                <label for="brand-active">Active</label>
            </div>
        </div>
    </div>
    <hr>
    @if(\Auth::user()->checkAccess("Branch Details", "E"))
    <div class="form-group text-right">
        <div class="col-md-12">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </div>
    @endif
</form>