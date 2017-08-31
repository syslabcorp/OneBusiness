@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4>Create new branch</h4>
            </div>
            <div class="panel-body" style="margin-top: 30px;">
                <form action="{{ route('branchs.store') }}" method="POST" class="col-md-12 form-horizontal" novalidate>
                    {{ csrf_field() }}
                    <div class="form-group {{ $errors->has('branch_name') ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">Branch Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Branch Name" name="branch_name" value="{{ old('branch_name') }}">
                            @if($errors->has('branch_name'))
                            <span class="help-block">{{ $errors->first('branch_name') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('operator') ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">Operator</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Operator" name="operator" value="{{ old('operator') }}">
                            @if($errors->has('operator'))
                            <span class="help-block">{{ $errors->first('operator') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('street') ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">Street Address</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="Street Address" name="street" value="{{ old('street') }}">
                            @if($errors->has('street'))
                            <span class="help-block">{{ $errors->first('street') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('Prov_ID') ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">Province</label>
                        <div class="col-sm-10">
                            <select name="Prov_ID" id="select-province" class="form-control">
                                <option selected value="">Select Province</option>
                                @foreach(\App\Province::all() as $province)
                                    <option value="{{ $province->Prov_ID }}" {{ old('Prov_ID') == $province->Prov_ID ? 'selected' : ''}}>{{ $province->Province }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('Prov_ID'))
                            <span class="help-block">The Province field is required</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('City_ID') ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">City</label>
                        <div class="col-sm-10">
                            <select name="City_ID" id="select-city" class="form-control">
                                <option selected value="">Select City</option>
                                @foreach(\App\City::all() as $city)
                                    <option data-province="{{ $city->Prov_ID }}" value="{{ $city->City_ID }}"
                                        {{ old('City_ID') == $city->City_ID ? 'selected' : ''}}>{{ $city->City }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('City_ID'))
                            <span class="help-block">The City field is required</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group {{ $errors->has('units') ? 'has-error' : '' }}">
                        <label class="col-sm-2 control-label">No. Of Units</label>
                        <div class="col-sm-10">
                            <input type="number" name="units" class="form-control" placeholder="No. Of Units" value="{{ old('units') }}">
                            @if($errors->has('units'))
                            <span class="help-block">{{ $errors->first('units') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Active</label>
                        <div class="col-sm-10">
                            <div class="control-checkbox">
                                <input type="checkbox" id="brand-active" name="active" {{ old('active') == 1 ? 'checked' : ''}} value="1">
                                <label for="brand-active">Active</label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-md-12">
                            <a href="{{ route('branchs.index') }}" class="btn btn-default pull-left">
                                <i class="fa fa-reply"></i> Back
                            </a>
                            <button type="submit" class="btn btn-success pull-right">Create</button>
                        </div>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
</section>
@endsection