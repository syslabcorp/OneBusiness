@extends('layouts.custom') @section('content')
<form action="{{ route('provinces.update', $province) }}" method="POST">
  {{ csrf_field() }}
  <input type="hidden" name="_method" value="PUT">
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="row">
        <div class="col-md-6 col-xs-6">
          Edit Province
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="rown">
        <div class="col-sm-6 col-sm-offset-3">
          <div class="form-group">
            <label>Province</label>
            <input name="Province" type="text" class="form-control" value="{{ $province->Province }}">
            @if($errors->has('Province'))
              <span class="error">{{ $errors->first('Province') }}</span>
            @endif
          </div>
        </div>
      </div>
      
    </div>
    <div class="panel-footer">
      <div class="rown">
        <div class="col-sm-6">
          <a href="{{ route('provinces.index') }}" class="btn btn-md btn-default">
            <i class="fas fa-reply"></i> Back
          </a>
        </div>
        <div class="col-sm-6 text-right">
          <button class="btn btn-md btn-success">
            <i class="far fa-save"></i> Update
          </button>
        </div>
      </div>
    </div>
  </div>
</form>
@endsection